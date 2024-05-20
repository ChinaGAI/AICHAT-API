<?php

namespace App\Http\Controllers\api\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ConfigService;

class ConfigController extends Controller
{
    public function all_config()
    {
        $config = ConfigService::getAll();
        $data = [
            'password_login_enable'=>$config['password_login_enable'],
            'wechat_login_enable'=>$config['wechat_login_enable'],
            'sms_login_enable'=>$config['sms_login_enable'],
            'mail_enable'=>$config['mail_enable'],
            'captcha_enable'=>$config['captcha_enable'],
            'captcha_type'=>$config['captcha_config']['type'],
            'captcha_config_id'=>$config['captcha_config']['id'],
            'image_url'=>$config['upload_config']['base_url'],
            'pay_config'=>$config['pay_config']
        ];
        $return = [
            'code' => 200,
            'data'=>$data
        ];
        return  response()->json($return);
    }
}
