<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\HomeUser;
use \App\Http\Requests\RegisterCheckRequest;
use App\Models\Sms;
use Illuminate\Http\Request;
use App\Models\UserLikeRole;
use App\Models\ChatRole;




class UserController extends Controller
{
    /**
     * @param RegisterCheckRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/8 20:51
     */
    public function register(RegisterCheckRequest $request)
    {
        $data = $request->all();
        unset($data['repass']);
        if(!empty($data['code'])){
            $isValid = Email::isValidCode($data['email'],$data['code'],$request->getClientIp());
            if (!$isValid){
                return response()->json(['code'=>500,'message'=>'验证过期或者错误,请重试']);
            }
        }
        unset($data['code']);

        $data['password']=md5($data['password'].env('OB_CODE'));
        HomeUser::insert($data,$request->getClientIp());
        return response()->json(['code'=>200,'message'=>'注册完成,请去登陆']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/11 14:26
     */
    public function user_data(request $request)
    {

        $data = HomeUser::find($request->input('user_id'));

        $return = [
            'code'=>200,
            'data'=>$data
        ];
        return response()->json($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/14 18:16
     */
    public function role_like(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);

        $list = UserLikeRole::whereHas('role',function ($query){
            $query->where('is_delete', 0);
            })  ->with('role')
                ->where('user_id',$request->input('user_id'))
                ->where('state','正常')
                ->orderBy('sort_num','desc')
                ->paginate($page_size,['*'],'page',$page)->toArray();

        foreach ($list['data'] as $k=>$v){
            $list['data'][$k]=$v['role'];
        }
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/4/7 13:52
     */
    public function my_role(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $list = ChatRole::select('id','icon','name','desc','hello_msg','suggestions','user_id')->with('user:id,nickname,avatar')->where('is_delete',0)->where('user_id',$request->input('user_id'))->orderby('created_at','desc')->paginate($page_size,['*'],'page',$page)->toArray();
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * @author:阿文
     * @date:2024/5/5 14:57
     */
    public function bind(request $request)
    {
        $data = $request->all();
        $user = HomeUser::find($request->input('user_id'));
        if ($data['type']=='email'){
            $checkCode = Email::isValidCode($data['email'],$data['code'],$request->getClientIp());
            if ($checkCode){
                $user->email=$data['email'];
                $user->save();
                return response()->json(['code'=>200,'message'=>'邮箱绑定成功']);
            }
        }elseif($data['type']=='phone'){
            $checkCode = Sms::isValidCode($data['phone'],$data['code'],$request->getClientIp());
            if ($checkCode){
                $user->phone=$data['phone'];
                $user->save();
                return response()->json(['code'=>200,'message'=>'手机绑定成功']);
            }
        }
        return response()->json(['code'=>500,'message'=>'绑定失败']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/5/6 13:05
     */
    public function update_data(request $request)
    {
        $user = HomeUser::find($request->input('user_id'));
        $user->avatar = $request->input('avatar');
        $user->nickname = $request->input('nickname');
        try {
            $user->save();
        }catch(\Exception $e){
            return response()->json(['code'=>500,'message'=>'修改失败']);
        }

        return response()->json(['code'=>200,'message'=>'修改成功']);
    }
}
