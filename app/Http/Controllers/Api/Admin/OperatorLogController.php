<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperatorLog as Opermodel;


class OperatorLogController extends Controller
{
    //

    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $user_id   =$request->input('user_id',null);
        $remark  =$request->input('remark',null);
        $where = [];
        if (!empty($user_id)){
            $where[] = ['user_id','=',$user_id];
        }
        if (!empty($remark)){
            $where[] = ['remark','like','%'.$remark.'%'];
        }

        $list =  Opermodel::with('user')->orderBy('id','desc')->where($where)->paginate($page_size,['*'],'page',$page)->toArray();

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
