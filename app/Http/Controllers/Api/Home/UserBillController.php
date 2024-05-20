<?php

namespace App\Http\Controllers\api\Home;

use App\Http\Controllers\Controller;
use App\Models\HomeOrders;
use Illuminate\Http\Request;
use App\Models\HomeUserTokenRecords;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserBillController extends Controller
{
    /**获取不同类型的token记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/23 16:34
     */
    public function token(request $request)
    {
        $where = [];
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $desc = $request->input('desc',null);
        if (!empty($desc)){
            $where[] = ['desc','=',$desc];
        }
        $list = HomeUserTokenRecords::where($where)->orderby('created_at','desc')->where('user_id',$request->input('user_id'))->paginate($page_size,['*'],'page',$page)->toArray();
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

    /**获取订单记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/23 16:34
     */
    public function order(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $list = HomeOrders::where('user_id',$request->input('user_id'))->orderby('created_at','desc')->paginate($page_size,['*'],'page',$page)->toArray();
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

    /**获取token描述
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/23 16:34
     */
    public function get_token_desc()
    {
        $token_desc =[
            'code'=>200,
            'message'=>'',
            'data'=>[
                '套餐增加','签到增加','活动赠送','系统增加','系统减少','对话消费'
            ]
        ];
        return  response()->json($token_desc);
    }

    /**获取最近七天token量记录
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/23 17:41
     */
    public function get_week_token(request $request)
    {
        // 使用Carbon库获取当前日期和七天前的日期
        $now = \Carbon\Carbon::now()->endOfDay(); // 包括今天
        $weekAgo = \Carbon\Carbon::now()->subDays(6)->startOfDay();


        // 初始化一个数组来存储每天的token总量
        $dailyTokens = [];
        $user_id = $request->input('user_id');
        $totalTokens= 0;
        // 遍历每一天
        for ($date = $weekAgo; $date->lte($now); $date->addDay()) {

            // 让数据库服务器为这一天计算token总量
            $totalTokens = HomeUserTokenRecords::where('created_at', '>=', $date->startOfDay()->toDateTimeString())
                ->where('created_at', '<=', $date->endOfDay()->toDateTimeString())
                ->where('type', 'reducing')
                ->where('user_id', $user_id)
                ->sum('amount');

            // 将这一天的日期和token总量添加到数组中
            $dailyTokens[] = [
                'date' => $date->toDateString(),
                'tokens' => intval($totalTokens),
            ];
        }

        // 将结果返回给前端
        $data = [
            'code' => 200,
            'message' => '',
            'data' => $dailyTokens,
        ];
        return response()->json($data);
    }
}
