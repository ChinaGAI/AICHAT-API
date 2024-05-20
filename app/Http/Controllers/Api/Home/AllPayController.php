<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\HomeOrders;
use App\Models\HomeShop;
use App\Models\HomeUser;
use App\Models\HomeUserTokenRecords;
use Illuminate\Http\Request;
use Yansongda\LaravelPay\Facades\Pay;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class AllPayController extends Controller
{
    public function alipay(request $request)
    {

        $order_id = $request->input('order_id',null);
        $h5 = $request->input('h5',null);
        if (empty($order_id)){
            return response()->json(['code'=>500,'message'=>'订单id不存在']);
        }
        $order= HomeOrders::find($order_id);
        if (!$order){
            return response()->json(['code'=>500,'message'=>'订单不存在']);
        }
        $sub_order = [
            'out_trade_no' => $order->id,
            'total_amount' => $order->total_amount,
            'subject' => 'token套餐',
        ];
        if (!empty($h5)){
            return Pay::alipay()->h5($sub_order);
        }else{
            return Pay::alipay()->web($sub_order);
        }

    }
    public function wechatPay(request $request)
    {
        $order_id = $request->input('order_id',null);
        $h5 = $request->input('h5',null);
        $order= HomeOrders::find($order_id);
        if (!$order){
            return response()->json(['code'=>500,'message'=>'订单不存在']);
        }

        if (empty($h5)){
            $sub_order = [
                'out_trade_no' => $this->clear_uuid($order_id),
                'description' => 'token套餐',
                'amount' => [
                    'total' => intval($order->total_amount*100),
                ],
            ];
            $return = json_decode(Pay::wechat()->scan($sub_order),true);
            $img =  QrCode::format('png')->size(500)->generate($return['code_url']);    //format 是指定生成文件格式  默认格式是svg,可以直接在浏览器打开，png不能直接显示
            $qrCode = 'data:image/png;base64,' . base64_encode($img );
            return response()->json(['code'=>200,'message'=>'','data'=>['url'=>$qrCode]]);
        }else{
            $sub_order = [
                'out_trade_no' => $this->clear_uuid($order_id),
                'description' => 'token套餐',
                'amount' => [
                    'total' => intval($order->total_amount*100),
                ],
                'scene_info' => [
                    'payer_client_ip' => $request->getClientIp(),
                    'h5_info' => [
                        'type' => 'Wap',
                    ]
                ],
            ];
            $return =  json_decode(Pay::wechat()->h5($sub_order),true);
            return response()->json(['code'=>200,'message'=>'','data'=>['url'=>$return['h5_url']]]);
        }

    }
    /**
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|string
     * @author:阿文
     * @date:2024/4/29 16:17
     */
    public function alipay_callback(request $request)
    {
        try{
            $data = Pay::alipay()->callback();
            $order = HomeOrders::where('status','Pending')->find($data['out_trade_no']);
            if (empty($order)){
                return 'error';
            }
            if ($order['total_amount']!=$data['total_amount']){
                return 'error';
            }
            $order->updated_at = date('Y-m-d H:i:s');
            $order->status = 'Success';
            $order->save();
            $user = HomeUser::find($order->user_id);
            $user->tokens = $user->tokens + $order->token;
            $user->all_tokens = $user->all_tokens + $order->token;
            $user->save();
            $insert_data = [
                'user_id'=>$order->user_id,
                'amount'=>$order->token,
                'type'=>'add',
                'created_at'=>date('Y-m-d H:i:s'),
                'desc'=>'套餐增加',
                'balance'=>$user->tokens
            ];
            HomeUserTokenRecords::create($insert_data);
        } catch (\Exception $e) {
            //  return $e->getMessage();
            return 'error';
        }
        return Pay::alipay()->success();
    }
    public function wechat_callback(request $request)
    {
        try{
            $data = Pay::wechat()->callback();
            $order = HomeOrders::where('status','Pending')->find($this->add_uuid($data['resource']['ciphertext']['out_trade_no']));
            if (empty($order)){
                return 'error';
            }
            if ($order['total_amount']*100!=$data['resource']['ciphertext']['amount']['total']){
                return 'error';
            }
            $order->updated_at = date('Y-m-d H:i:s');
            $order->status = 'Success';
            $order->save();
            $user = HomeUser::find($order->user_id);
            $user->tokens = $user->tokens + $order->token;
            $user->all_tokens = $user->all_tokens + $order->token;
            $user->save();
            $insert_data = [
                'user_id'=>$order->user_id,
                'amount'=>$order->token,
                'type'=>'add',
                'created_at'=>date('Y-m-d H:i:s'),
                'desc'=>'套餐增加',
                'balance'=>$user->tokens
            ];
            HomeUserTokenRecords::create($insert_data);
        } catch (\Exception $e) {
            //  return $e->getMessage();
            return 'error';
        }
        return Pay::wechat()->success();
    }
    private function clear_uuid($uuid)
    {
        return Str::of($uuid)->replace('-', '')->value();
    }
    private function add_uuid($uuid)
    {
        $uuidWithHyphens = substr_replace($uuid, '-', 8, 0);
        $uuidWithHyphens = substr_replace($uuidWithHyphens, '-', 13, 0);
        $uuidWithHyphens = substr_replace($uuidWithHyphens, '-', 18, 0);
        $uuidWithHyphens = substr_replace($uuidWithHyphens, '-', 23, 0);
        return $uuidWithHyphens;
    }

}
