<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeUserTokenRecords;
use Illuminate\Http\Request;

class UserTokenController extends Controller
{
    /**查询用户token记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/22 17:21
     */
    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $list = HomeUserTokenRecords::orderby('created_at','desc')->paginate($page_size,['*'],'page',$page)->toArray();
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
