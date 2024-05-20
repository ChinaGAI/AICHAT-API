<?php

namespace App\Services;

class ConfigService
{
    /**
     * @param $key
     * @return mixed
     * @author:阿文
     * @date:2024/3/6 17:17
     */
    public static function getConfigValue($key)
    {
        //为什么这么写的 因为需求奇怪----
        $Config=  json_decode(file_get_contents(base_path('storage/app/config.json')),true);

        return $Config[$key];
    }
    /**
     * @param $key
     * @return mixed
     * @author:阿文
     * @date:2024/3/5 21:25
     */
    public static function getMailConfig($key)
    {
        $mailConfig=  json_decode(file_get_contents(base_path('storage/app/config.json')),true)['mail_config'];

        return $mailConfig[$key];
    }
    public static function getAll()
    {
        $Config=  json_decode(file_get_contents(base_path('storage/app/config.json')),true);
        return $Config;
    }
}
