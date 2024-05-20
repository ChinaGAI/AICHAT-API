<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoginLog;
use App\Models\AdminUser;
use App\Models\AdminUserToken;



class LoginController extends Controller
{


    /** 网站登陆接口
     * @param Request $request
     * @return void
     * @author:阿文
     * @date:2024/1/7 11:23
     */
    public function login_data_check(request $request)
    {

        $token=0;
        $data = $request->all();
        $state = 500;
        $remark='当前ip:'.$request->ip().',设备:'.$request->header('User-Agent');

        if (!empty($data['username'])&&!empty($data['password']))
        {

                 //用户开始校验
                    $re = AdminUser::where('username',$data['username'])->where('state',1)->first();

                        if (!empty($re)&&md5($data['password'].env('OB_CODE'))==$re['pwd']){

                            $token = md5($data['username'].time());
                            $insert = [
                                'token'=>$token,
                                'user_id'=>$re['id'],
                                'state'=>1,
                                'expiration_time'=>time()+86400
                            ];


                            AdminUserToken::where('user_id',$re['id'])->update(['state'=>0]);

                            AdminUserToken::create($insert);

                            $state=200;
                            $message='登陆成功';
                        }else{
                            $message='账号或者密码错误';
                        }

        }else{
            $message='靓仔别攻击了哟';
        }
        $return = [
            'code'=>$state,
            'message'=>$message,
            'data'=>[
                'token'=>$token
            ]
        ];
        LoginLog::created(['remark'=>$remark,'ip'=>$request->ip(),'state'=>$state]);
        //记录下请求并且返回前端信息

        return $return;
    }
}
