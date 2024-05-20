<?php
namespace App\Services;

use App\Services\ConfigService;

class GeetestService
{
        public static   function verify($data)
        {
            $config = ConfigService::getConfigValue('captcha_config');
            $lot_number = $data['lot_number'];
            $captcha_output = $data['captcha_output'];
            $pass_token = $data['pass_token'];
            $gen_time = $data['gen_time'];
            $sign_token = hash_hmac('sha256', $lot_number, $config['key']);
            $query = array(
                "lot_number" => $lot_number,
                "captcha_output" => $captcha_output,
                "pass_token" => $pass_token,
                "gen_time" => $gen_time,
                "sign_token" => $sign_token
            );
            $url = sprintf( "http://gcaptcha4.geetest.com/validate" . "?captcha_id=%s", $config['id']);
            $res = self::post_request($url,$query);
            if($res['status']=='success'){
                return true;
            }else{
                return false;
            }
        }
    public  static function  post_request($url,$query)
    {
        $data = http_build_query($query);

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded",
                'content' => $data,
                'timeout' => 5
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return json_decode($result,true);
    }
}

