<?php

namespace App\Http\Controllers\api\Work;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GatewayWorker\Lib\Gateway;
use OpenAI;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\HomeUserToken;
use App\Models\ChatClientIdLog;

class ChatController extends Controller
{
    public    function demo(request $request)
    {
       // $token = $request->input('token');
        $sendMessage = json_encode([
            'code' => 200,
            'messages' => '测试u',
            'data' => [],
        ]);

        $key = 'sk-lQr3DANrnjr039IRIEE6pzIzs3tEWcer0GHe9QtdL1koWtew';
        $json = [
            'model'=>'moonshot-v1-8k',
            'messages'=>[
                ['role'=>'user','content'=>'你好']
            ],
            'temperature'=>0.3
        ];
        $client = OpenAI::factory()
            ->withApiKey($key)
            ->withBaseUri('api.moonshot.cn/v1') // default: api.openai.com/v1
            ->withHttpClient($client = new \GuzzleHttp\Client([])) // default: HTTP client found using PSR-18 HTTP Client Discovery
            ->withHttpHeader('Content-Type', 'application/json')
            ->withHttpHeader('Authorization', 'Bearer '.$key)
            ->withStreamHandler(fn (RequestInterface $request): ResponseInterface => $client->send($request, [
                'stream' => true // Allows to provide a custom stream handler for the http client.
            ]))
            ->make();
        $result = $client->chat()->createStreamed($json);
        foreach ( $result->getIterator() as $k=>$item){
            Gateway::sendToClient('7f00000108fc00000033',json_encode(['content'=>$item->choices[0]->delta->content,'Status'=>'Replying']));
        }
    }

    /**验证token是否过期可使用
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/19 16:06
     */
    public function check(request $request)
    {
            $token = $request->input('token');
        $tokenData = HomeUserToken::where('token', $token)->where('expiration_time', '>', now())->where('state',1)->first(['user_id','expiration_time']);
        if ($tokenData){
            $data=[
              'code'=>200,
              'user_id'=>$tokenData['user_id'],
                'expiration_time'=>$tokenData['expiration_time']
            ];
        }else{
            $data=[
                'code'=>500
            ];
        }

        return response()->json($data);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/26 16:45
     */
    public function bind_client_id(request $request)
    {
        $data = [];
        $data['user_id'] = $request->input('user_id');
        $data['client_id'] = $request->input('client_id');
        $data['expiration_time'] = $request->input('expiration_time');
        $data['created_at']=now();
        $re = ChatClientIdLog::create($data);
        if (!$re){
            return response()->json(['code'=>500,'message'=>'绑定失败']);
        }
        return response()->json(['code'=>200,'data'=>['id'=>$re['id']]]);
    }
}
