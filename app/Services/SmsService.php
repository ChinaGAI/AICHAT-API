<?php

namespace App\Services;

use App\Services\ConfigService;

class SmsService
{
    /**
     * @param $phone
     * @param $type
     * @param $insert
     * @return void
     * @author:阿文
     * @date:2024/3/6 13:03
     */
    public static function send_sms($phone,$type,$code)
    {
        $config = ConfigService::getConfigValue('sms_config');
        $content= self::getContent($config['header'],$type,$code);
        $sendurl = 'http://api.smsbao.com/sms?u='.$config['web_user'].'&p='.$config['web_pwd'].'&m='.$phone.'&c='.$content;
        $result=file_get_contents($sendurl);
        if($result=='0'){
            return true;
        }else{
            return false;
        }
    }
    /**
     * @param $type
     * @return string
     * @author:阿文
     * @date:2024/3/6 0:28
     */
    private static function getContent($header,$type,$code)
    {
        switch  ($type)
        {
            case 'register':
                $type = '注册';
                break;
            case 'bind':
                $type = '绑定';
                break;
            case 'password':
                $type = '重置密码';
                break;
            case 'login':
                $type = '登陆';
                break;
            default:
                $type = 'error';
        }
        return urlencode('【'.$header."】亲爱的用户您正在进行{$type}，您的验证码是{$code}。有效期为30分钟，如非本人操作，请忽略本短信");
    }
}
