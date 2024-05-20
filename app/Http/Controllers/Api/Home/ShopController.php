<?php

namespace App\Http\Controllers\api\Home;

use App\Http\Controllers\Controller;
use App\Models\HomeUser;
use App\Models\HomeUserTokenRecords;
use Illuminate\Http\Request;
use App\Models\HomeShop;
use App\Models\HomeOrders;
use Yansongda\LaravelPay\Facades\Pay;

class ShopController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/23 23:36
     */
    public function shop_list(request $request)
    {

        $list = HomeShop::select('id','title','tokens','price','desc','origin_price')->where('enable',1)->orderby('price')->get();

        return response()->json(['code'=>200,'data'=>$list]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/24 13:29
     */
    public function pay(request $request)
    {
        $user_id = $request->input('user_id');
        $pay_type= $request->input('pay_type',null);
        $shop_id = $request->input('shop_id',null);
        if(empty($pay_type)||empty($shop_id)||!in_array($pay_type,['Wechat','Alipay'])){
            return response()->json(['code'=>500,'message'=>'参数错误']);
        }
        $model= HomeShop::find($shop_id);
        if (!$model){
            return response()->json(['code'=>500,'message'=>'商品不存在']);
        }
        $createArray=[
            'user_id'=>$user_id,
            'shop_id'=>$shop_id,
            'total_amount'=>$model['price'],
            'status'=>'Pending',
            'pay_type'=>$pay_type,
            'token'=>$model['tokens'],
            'created_at'=>date('Y-m-d H:i:s')
        ];
        $order = HomeOrders::create($createArray);

        if ($order){
            return response()->json(['code'=>200,'message'=>'订单创建成功','data'=>['order_id'=>$order->id]]);
        }else{
            return response()->json(['code'=>500,'message'=>'订单创建失败']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/24 17:33
     */
    public function pay_confirm(request $request)
    {
        $order_id = $request->input('order_id');
        $order = HomeOrders::find($order_id);
        if (!$order){
            return response()->json(['code'=>500,'message'=>'订单不存在']);
        }
        return response()->json(['code'=>200,'data'=>['order_id'=>$order->id,'status'=>$order['status']]]);
    }


}
