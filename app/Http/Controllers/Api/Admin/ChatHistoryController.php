<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatHistory;

class ChatHistoryController extends Controller
{
    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $user_id   =$request->input('user_id',null);
        $chat_id = $request->input('chat_id',null);
        $where = [];
        if (!empty($user_id)){
            $where[] = ['user_id','=',$user_id];
        }
        if (!empty($chat_id)){
            $where[] = ['chat_id','=',$chat_id];
        }

        $list =  ChatHistory::with('role')->orderBy('id','desc')->where($where)->paginate($page_size,['*'],'page',$page)->toArray();

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
