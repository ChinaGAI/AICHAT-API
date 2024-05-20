<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatItems;

class ChatItemsController extends Controller
{
    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $title  =$request->input('title',null);
        $where = [];

        if (!empty($title)){
            $where[] = ['title','like','%'.$title.'%'];
        }

        $list = ChatItems::orderBy('id','desc')->where($where)->paginate($page_size,['*'],'page',$page)->toArray();
        $data =[
            'code'=>200,
            'message'=>'',
            'data'=>[
                'list'=>$list['data'],
                'total'=>$list['total'],
                'current'=>intval($page),
                'size'=>intval($page_size)
            ]
        ];
        return  response()->json($data);
    }
}
