<?php

namespace App\Events;


use GatewayWorker\BusinessWorker;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\Log;
use Workerman\Lib\Timer;
use Illuminate\Support\Str;
use App\Services\ConfigService;
use Illuminate\Support\Facades\Http;

class Workerman
{

        /**
     * 业务服务启动事件
     * @param BusinessWorker $businessWorker
     * @return void
     */
    public static function onWorkerStart(BusinessWorker $businessWorker)
    {

        self::log(__FUNCTION__, $businessWorker->workerId);
        Timer::add(1, function () use ($businessWorker) {
            $time_now = time();
            foreach ($businessWorker->connections as $connection) {
                // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                if (empty($connection->lastMessageTime)) {
                    $connection->lastMessageTime = $time_now;
                    continue;
                }
                // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
                if ($time_now - $connection->lastMessageTime > 30) {
                    if ($connection->id) {
                        //todo
                    }
                    //断开后的回调
                    echo "Client ip {$connection->getRemoteIp()} timeout!!!\n";
                    $connection->close();
                }
            }
        });
    }

    /**
     * 客户端连接事件 进行鉴权看看
     * @param string $clientId
     * @return void
     */
    public static function onConnect(string $clientId)
    {

//        if (!isset($_GET['access_token'])) {
//            self::log(__FUNCTION__.'错误数据,中止链接',['code'=>500]);
//            Gateway::destoryClient($clientId);
//            return;
//        }
//        self::log(__FUNCTION__.'错误数据,中止链接'.$_GET['access_token'],['code'=>500]);
//        $domain = ConfigService::getConfigValue('website_url');
//        $response = json_decode(Http::post($domain.'/api/work/check',$_GET),true);


        self::log(__FUNCTION__,$clientId);
    }

    /**
     * 客户端websocket 连接事件 触发验证
     * @param string $clientId
     * @param mixed $data
     * @return void
     */
    public static function onWebSocketConnect(string $clientId, $data)
    {

        $token = $data['get']['token'] ?? null;
        if (!$token){
            Gateway::destoryClient($clientId);
        }else{
            $domain = ConfigService::getConfigValue('website_url');
            $response = json_decode(Http::post($domain.'/api/work/check',$data['get']),true);
            self::log(__FUNCTION__, $clientId, $response);
            if ($response['code']!=200){
                Gateway::destoryClient($clientId);
            }else{
                //记录client id 记录
                $user_client = [
                  'user_id'=>$response['user_id'],
                  'client_id'=>$clientId,
                    'expiration_time'=>$response['expiration_time']
                ];
                $re=Http::post($domain.'/api/work/bind_client_id',$user_client);
                $reDecode=json_decode($re,true);
                if ($reDecode['code']==500){
                    Gateway::destoryClient($clientId);
                }else{
                    Gateway::sendToClient($clientId,json_encode(['code'=>200,'type'=>'config','data'=>['client_id'=>$reDecode['data']['id']]]));
                }

            }
        }

    }

    /**
     * 客户端websocket消息
     * @param string $clientId
     * @param string $messageJson
     * @return void
     */
    public static function onMessage(string $clientId,  $messageJson)
    {
        //用户鉴权成功开始处理逻辑

//
//        $message = json_decode($messageJson,true);
//        if(empty($message)||empty($message['content'])){
//            self::log('发送消息过来的时候1',$messageJson);
//            self::sendMessage(500, '信息不能为空');
//            self::log('解析后的json',$message['content']);
//            return;
//        }
//        $key = 'sk-lQr3DANrnjr039IRIEE6pzIzs3tEWcer0GHe9QtdL1koWtew';
//
//        $json = [
//            'model'=>'moonshot-v1-8k',
//            'messages'=>[
//                ['role'=>'user','content'=>$message['content']]
//            ],
//            'temperature'=>0.3
//        ];
//
//        $client = OpenAI::factory()
//            ->withApiKey($key)
//            ->withBaseUri('api.moonshot.cn/v1') // default: api.openai.com/v1
//            ->withHttpClient($client = new \GuzzleHttp\Client([])) // default: HTTP client found using PSR-18 HTTP Client Discovery
//            ->withHttpHeader('Content-Type', 'application/json')
//            ->withHttpHeader('Authorization', 'Bearer '.$key)
//            ->withStreamHandler(fn (RequestInterface $request): ResponseInterface => $client->send($request, [
//                'stream' => true // Allows to provide a custom stream handler for the http client.
//            ]))
//            ->make();
//
//
//        $result = $client->chat()->createStreamed($json);
//       // $data = $result->getIterator()->current();
//        //self::log('打印流id',$client->threads()->messages());
//        self::sendMessage(200, 'ok',['Status'=>'Start']);
//        foreach ( $result->getIterator() as $k=>$item){
//            self::sendMessage(200, 'ok',['content'=>$item->choices[0]->delta->content,'Status'=>'Replying']);
//        }
//        self::sendMessage(200, 'ok',['Status'=>'End']);
//        if (empty($message['type'])) {
//            self::sendMessage(500, '请配置type');
//            return;
//        }
//        switch ($message['type']) {
//            case 'chat':
//
//                break;
//            case 'ping':
//                self::sendMessage(200, 'ok');
//                break;
//            default:
//                self::sendMessage(500, '消息类型不支持');
//        }
    }


    /**
     * 关闭客户端websocket
     * @param string $clientId
     * @return void
     */
    public static function onClose(string $clientId)
    {
        self::log(__FUNCTION__, $clientId);
        Gateway::destoryClient($clientId);
    }


    /**
     * 写日志
     * @param string $title
     * @param $data
     * @return void
     */
    protected static function log(string $title, ...$data): void
    {
        if (config('app.debug')) {
            var_dump("========== {$title} ==========");
            var_dump($data);
            Log::info("{$title} | " . json_encode($data, 256));
        }
    }


    /**
     * 发送客户端消息
     * @param int $code
     * @param mixed $message
     * @param array|null $data
     * @param string $clientId
     * @return void
     */
    protected static function sendMessage(int $code, $message, ?array $data = null, string $clientId = ''): void
    {
        $sendMessage = json_encode([
            'code' => $code,
            'messages' => $message,
            'data' => $data,
        ]);
        if ($clientId)
            Gateway::sendToClient($clientId, $sendMessage);
        else
            Gateway::sendToCurrentClient($sendMessage);
    }
    protected static function onError($e)
    {
        self::log(__FUNCTION__, $e);
    }
}
