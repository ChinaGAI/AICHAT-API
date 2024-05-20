<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginCheckRequest;
use App\Http\Requests\MailSendCheckRequest;
use App\Models\Email;
use App\Models\HomeLoginLog;
use App\Services\MailService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use App\Models\Sms;
use App\Models\HomeUser;
use App\Models\HomeUserToken;
use App\Services\ConfigService;
use App\Services\GeetestService;
use App\Http\Requests\SmsSendCheckRequest;
use App\Http\Requests\LoginSmsCheckRequest;


class LoginController extends Controller
{

    /**获取图片验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/7 18:35
     */
    public function captcha(request $request)
    {

        $config = ConfigService::getAll();
        if($config['captcha_enable']){
            $data = app('captcha')->create('default', true);

            return response()->json(['code'=>200,'message'=>'','data'=>$data]);
        }
        return response()->json(['code'=>500,'message'=>'未开启验证码']);
    }

    /**
     * @param $captcha
     * @param $key
     * @return bool|void
     * @author:阿文
     * @date:2024/3/8 16:22
     */
    private function captcha_check($captcha,$key)
    {
        $config = ConfigService::getAll();
        if($config['captcha_enable']){

            if (!captcha_api_check($captcha , $key)){
                return false;
            }
            return true;
        }
    }

    /**
     * @param SmsSendCheckRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/8 16:22
     */
    public function send_sms(SmsSendCheckRequest $request)
    {
        $captcha_all=$request->input('captcha');
        $scene=$request->input('scene');
        $phone=$request->input('phone');
        if(empty($captcha_all['key'])){
            $state =GeetestService::verify($captcha_all);
        }else{
            $state = $this->captcha_check($captcha_all['code'],$captcha_all['key']);
        }
        if(!$state){
            return response()->json(['code'=>500,'message'=>'校验失败，请重试']);
        }
        $code = rand(100000,999999);
        $user_id = HomeUser::where('phone',$phone)->value('id');
        $return  = Sms::insert($request->getClientIp(),$phone,$scene,$code,$user_id);

        if(!$return['state']){
            return response()->json(['code'=>500,'message'=>$return['message']]);
        }
        SmsService::send_sms($phone,$scene,$code);
        return response()->json(['code'=>200,'message'=>'发送完成']);
    }

    /**
     * @param MailSendCheckRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/8 16:21
     */
    public function send_email(MailSendCheckRequest $request)
    {
        $email = $request->input('email');
        $scene=$request->input('scene');
        $captcha_all=$request->input('captcha');
        $config = ConfigService::getAll();
        if($config['mail_enable']){
            if(empty($captcha_all['key'])){
                $state =GeetestService::verify($captcha_all);
            }else{
                $state = $this->captcha_check($captcha_all['code'],$captcha_all['key']);
            }
            if(!$state){
                return response()->json(['code'=>500,'message'=>'校验失败，请重试']);
            }
            $code = rand(100000,999999);
            $user_id = HomeUser::where('email',$email)->value('id');
            $return  = Email::insert($request->getClientIp(),$email,$request->input('scene'),$code,$user_id);
            if(!$return['state']){
                return response()->json(['code'=>500,'message'=>$return['message']]);
            }

            MailService::sendMail($email,['code'=>$code],$scene);
            return response()->json(['code'=>200,'message'=>'发送完成']);
        }
        return response()->json(['code'=>500,'message'=>'未开启邮件验证']);
    }

    /**
     * @param LoginCheckRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/11 11:24
     */
    public function login(LoginCheckRequest $request)
    {
        $user = HomeUser::existsByUsernameOrEmail($request->input('account'));
        if($user){
            $pwd = md5($request->input('password').env('OB_CODE'));
            if($pwd==$user->password){
                $user->last_ip=$request->getClientIp();
                $user->login_count+=1;
                $user->save();
                HomeLoginLog::insert($request->getClientIp(),'密码登陆',$user->id,'','登陆成功');
                $token = HomeUserToken::insert($request->input('account'),$request->getClientIp(),$user->id);
                $data = [
                  'token'=>$token
                ];
                return response()->json(['code'=>200,'message'=>'登陆成功','data'=>$data]);
            }
            HomeLoginLog::insert($request->getClientIp(),'密码登陆',$user->id,'尝试密码'.$request->input('password').'进行登陆','账号失败');
            return response()->json(['code'=>500,'message'=>'密码错误,请重试']);
        }
        $content = '输入账户：'.$request->input('account').'密码：'.$request->input('password').'尝试进行登陆';
        HomeLoginLog::insert($request->getClientIp(),'密码登陆',0,$content,'账户不存在');
        return response()->json(['code'=>500,'message'=>'账户不存在,请重试或者去注册']);

    }

    /**
     * @param LoginSmsCheckRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/9 22:48
     */
    public function login_sms(LoginSmsCheckRequest $request)
    {
        $data = $request->all();
        $checkCode = Sms::isValidCode($data['phone'],$data['code'],$request->getClientIp());
        if($checkCode){
            $user = HomeUser::where('phone',$data['phone'])->first();

            if (!empty($user)){
                $user->last_ip=$request->getClientIp();
                $user->login_count+=1;
                $user->save();
                HomeLoginLog::insert($request->getClientIp(),'短信登陆',$user->id,'','登陆成功');
                $token = HomeUserToken::insert($user['phone'],$request->getClientIp(),$user->id);
                $data = [
                    'token'=>$token
                ];
                return response()->json(['code'=>200,'message'=>'登陆成功','data'=>$data]);
            }else{
                unset($data['code']);
                $user=HomeUser::insert($data,$request->getClientIp());
                $token = HomeUserToken::insert($user['phone'],$request->getClientIp(),$user['id']);
                $data = [
                    'token'=>$token
                ];
                return response()->json(['code'=>200,'message'=>'登陆成功','data'=>$data]);
            }
        }
        return response()->json(['code'=>500,'message'=>'验证过期或者错误,请重试']);
    }

    public function pwd_reset(request $request)
    {
        $email =$request->input('email',null);
        $pwd = $request->input('password',null);
        $code = $request->input('code',null);
        if(empty($phone)||empty($pwd)||empty($code)){
            return response()->json(['code'=>500,'message'=>'验证过期或者错误,请重试']);
        }
        $user=HomeUser::where('email',$email)->first();
        if(empty($user)){
            return response()->json(['code'=>500,'message'=>'账户不存在']);
        }
        $where[]=['expiration_time','>',time()];
        $where[]=['code','=',$code];
        $where[]=['scene','=','reset'];
        $where[]=['phone','=',$phone];
        $re = Sms::where($where)->first();
        if(empty($re)){
            return response()->json(['code'=>500,'message'=>'验证码错误或者过期']);
        }
        $user->pwd = md5($pwd.env('OB_CODE'));
        $user->Save();
        return response()->json(['code'=>200,'message'=>'修改密码成功']);
    }
}
