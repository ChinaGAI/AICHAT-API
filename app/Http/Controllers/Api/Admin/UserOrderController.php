<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeOrders;
use App\Models\HomeUser;
use App\Models\HomeUserTokenRecords;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    /**展示用户订单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/22 17:09
     */
    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $status  =$request->input('status',null);
        $id  =$request->input('id',null);
        $where = [];

        if (!empty($status)){
            $where[] = ['status','=',$status];
        }
        if (!empty($id)){
            $where[] = ['id','=',$id];
        }
        $list = HomeOrders::with('user')->where($where)->orderby('created_at','desc')->paginate($page_size,['*'],'page',$page)->toArray();
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

    /**修改订单状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/22 17:17
     */
    public function update(request $request)
    {
        $data = HomeOrders::find($request->input('id'));
        if ($data->stuats == 'Success'){
            $data = [
                'code'=>500,
                'message'=>'订单已经处理,请勿重复操作'
            ];
            return Response()->json($data);
        }
        $data->updated_at = date('Y-m-d H:i:s');
        $data->status = 'Success';
        $re=$data->save();
        if ($re){
            try {

                $user = HomeUser::find($data->user_id);

                $user->tokens = $user->tokens + $data->token;
                $user->all_tokens = $user->all_tokens + $data->token;
                $user->save();
                $insert_data = [
                    'user_id'=>$data->user_id,
                    'amount'=>$data->token,
                    'type'=>'add',
                    'created_at'=>date('Y-m-d H:i:s'),
                    'desc'=>'套餐增加',
                    'balance'=>$user->tokens
                ];
                HomeUserTokenRecords::create($insert_data);
            }catch (\Exception $e){
                $data = [
                    'code'=>500,
                    'message'=>'保存用户信息失败,请检测信息'
                ];

                return Response()->json($data);
            }
            $data = [
                'code'=>200,
                'message'=>'修改订单状态成功'
            ];
            return Response()->json($data);
        }else{
            $data = [
                'code'=>500,
                'message'=>'修改订单状态失败,请检测信息'
            ];
            return Response()->json($data);
        }
    }
}
