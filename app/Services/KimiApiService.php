<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class KimiApiService
{
    protected $client;

    public function requestToKimi($message)
    {
        $web_url = 'https://api.moonshot.cn/v1/chat/completions';

        $key = 'sk-lQr3DANrnjr039IRIEE6pzIzs3tEWcer0GHe9QtdL1koWtew';
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $key",
        ];
        $data = [
            'model' => 'moonshot-v1-8k',
            'messages'=>[
                ['role'=>'system','content'=>'你是 Kimi，由 Moonshot AI 提供的人工智能助手，你更擅长中文和英文的对话。你会为用户提供安全，有帮助，准确的回答。同时，你会拒绝一些涉及恐怖主义，种族歧视，黄色暴力等问题的回答。Moonshot AI 为专有名词，不可翻译成其他语言'],
                ['role'=>'user','content'=>$message]
            ],
            'temperature' => 0.7,
            'max_tokens' => 1024, // 根据需要调整生成文本的长度
            'stream'=>true
        ];



        return 1;
    }

    protected function callback()
    {

    }

    protected function sseDO($data)
    {
        $lines = explode('\r\n',$data);
        $data = [];
        foreach ($lines as $line){
          [$event,$dataStr] =  explode(':',$line);
          var_dump($event,$dataStr);
          switch ($event){
              case 'event':
                  // TODO 可能有event的内容
                  break;
              case 'data':
                  // 处理返回数据
                  if($dataStr !== '[DONE]'){
                      $data[] = json_decode($dataStr,true);
                  }
                  break;
              default:

          }
        }
        return $data;
    }
}
