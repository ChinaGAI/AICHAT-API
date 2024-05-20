<?php

namespace App\Http\Controllers\api\Home;

use App\Http\Controllers\Controller;
use App\Models\ChatApiKey;
use App\Models\ChatRole;
use App\Models\HomeUser;
use App\Models\HomeUserTokenRecords;
use GatewayWorker\Lib\Gateway;
use Illuminate\Http\Request;
use App\Models\ChatItems;
use App\Models\ChatHistory;
use App\Models\ChatModels;
use App\Models\ChatClientIdLog;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use OpenAI;
use GuzzleHttp\Client;
use App\Http\Requests\MessgeCheckRequest;
use Illuminate\Support\Facades\Cache;



class ChatController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/13 18:50
     */
    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $list = ChatItems::with('role')->where('user_id',$request->input('user_id'))->where('state','正常')->orderby('updated_at','desc')->paginate($page_size,['*'],'page',$page)->toArray();
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
     * @date:2024/3/13 19:46
     */
    public function history(request $request)
    {
        $list = ChatHistory::where('chat_id',$request->input('chat_id'))->where('type','!=','delete')->where('user_id',$request->input('user_id'))->orderby('created_at')->get();
        return response()->json(['code'=>200,'data'=>$list]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/13 20:38
     */
    public function detail(request $request)
    {
        $chat_id = $request->input('chat_id');
        if(!$chat_id){
            return response()->json(['code'=>500,'message'=>'参数有误']);
        }
        $data = ChatItems::with('role')->where('user_id',$request->input('user_id'))->where('id',$chat_id)->where('state','正常')->first();
        if (empty($data)){
            return response()->json(['code'=>500,'message'=>'对话不存在']);
        }
        return response()->json(['code'=>200,'data'=>$data]);
    }

    /**请求模型创立新的对话
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Exception
     * @author:阿文
     * @date:2024/3/26 15:18
     */
    public function chat_new_message(MessgeCheckRequest $request)
    {
        // 获取新建对话的参数
        $model_id = $request->input('model_id');
        $role_id = $request->input('role_id','system');
        $client_id = $request->input('client_id');
        $use_context = $request->input('use_context');
        $content  = $request->input('content');
        $is_temp  = $request->input('is_temp',null);
        //查询对应的数据
        $chatModel = ChatModels::where('enabled',1)->find($model_id);
        $client = ChatClientIdLog::where('expiration_time','>',now())->find($client_id);
        $role = ChatRole::where('enabled',1)->find($role_id);
        if (!$chatModel||!$client){
            return response()->json(['code'=>500,'message'=>'参数有误']);
        }
        //判断用户是否有token 及apikey 是否启用
        $keyData = ChatApiKey::where('enabled',1)->find($chatModel['key_id']);
        if (!$keyData){
            return response()->json(['code'=>500,'message'=>'当前模型关闭,请联系客服处理']);
        }
        //查看是否有token
        $user = HomeUser::find($request->input('user_id'));
        if ($user->tokens<0){
            return response()->json(['code'=>500,'message'=>'tokens不足,请充值']);
        }
        $state_connet=Gateway::isOnline($client->client_id);
        if(!$state_connet){
            return response()->json(['code'=>502,'message'=>'客户端已离线,请重新连接']);
        }

        //这里应该先创建对话 不管是否推送出去消息
        $chat=ChatItems::create([
            'user_id'=>$request->input('user_id'),
            'model_id'=>$model_id,
            'role_id'=>$role_id,
            'client_id'=>$client->client_id,
            'title'=>mb_substr($content, 0, 10, 'UTF-8'),
            'state'=>empty($is_temp)?'正常':'临时',
            'use_context'=>$use_context,
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
        $chat->role = $role;
        return response()->json(['code'=>200,'data'=>['chat'=>$chat,'prompt_token'=>intval(strlen($content)*$chatModel->magnification)]]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @author:阿文
     * @date:2024/3/28 17:16
     */
    public function chat_message(request $request)
    {
        //获取chat_id 加载需要的数据
        $chat_id = $request->input('chat_id');
        $client_id = $request->input('client_id');
        $content = $request->input('content');
        $is_retry = $request->input('is_retry',null);
        if (empty($chat_id)||empty($client_id)||empty($content)){
            return response()->json(['code'=>500,'message'=>'参数有误']);
        }
        $getChatConfig = $this->get_chat_config($chat_id,$client_id);
        if ($getChatConfig['code']!=200){
            return response()->json($getChatConfig);
        }
        //参数全部获取到以后判断当前用户token是否足够
        $user = HomeUser::find($getChatConfig['data']['client']->user_id);
        //判断是否和当前用户id是否一致
        if ($user->id!=$request->input('user_id')){
            return response()->json(['code'=>500,'message'=>'用户信息有误']);
        }
        if ($user->tokens<0){
            return response()->json(['code'=>500,'message'=>'tokens不足,请充值']);
        }
        $state_connet=Gateway::isOnline($getChatConfig['data']['client']->client_id);
        if(!$state_connet){
            return response()->json(['code'=>502,'message'=>'客户端已离线,请重新连接']);
        }
        if(!empty($is_retry)&&$is_retry==true){
            $delete_data=ChatHistory::where('user_id',$request->input('user_id'))->where('chat_id',$chat_id)->where('type','!=','delete')->latest('updated_at')->first();
            if($delete_data){
                $delete_data->type = 'delete';
                $delete_data->deleted_at = now();
                $delete_data->save();
                if($delete_data->type=='reply'){
                    $prompt=ChatHistory::where('user_id',$request->input('user_id'))->where('chat_id',$chat_id)->where('type','prompt')->latest('updated_at')->first();
                    if ($prompt){
                        $prompt->type = 'delete';
                        $prompt->deleted_at = now();
                        $prompt->save();
                    }
                }
            }
        }
        //这里应该先增加历史记录对话 不管是否推送出去消息
        $chatHistory=ChatHistory::create([
            'chat_id'=>$chat_id,
            'user_id'=>$request->input('user_id'),
            'role_id'=>$getChatConfig['data']['role']->id,
            'content'=>$content,
            'type'=>'prompt',
            'tokens'=>intval(strlen($content)*$getChatConfig['data']['chatModel']->magnification),
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        //修改chatitem的修改时间
        ChatItems::where('id',$chat_id)->update(['updated_at'=>now()]);
        Cache::set($chatHistory->id, 'on', 600);
        //发送消息 获取历史信息
        $total_token=$this->sendMessage($getChatConfig['data']['chatModel'],$getChatConfig['data']['keyData'],$getChatConfig['data']['role'],$content,$getChatConfig['data']['client']->client_id,$request->input('user_id'),$getChatConfig['data']['role']->id,$chatHistory->id,$getChatConfig['data']['chat']);
        //回复消息以后开始进行扣token 包括发送和回复的token 并且增加到扣费记录
        $user->tokens = $user->tokens - intval(strlen($content)*$getChatConfig['data']['chatModel']->magnification)-$total_token;
        $user->save();
        if($getChatConfig['data']['chatModel']->magnification>0){
        //增加token消费的记录
        HomeUserTokenRecords::create([
            'chat_id'=>$chat_id,
            'user_id'=>$request->input('user_id'),
            'amount'=>intval(strlen($content)*$getChatConfig['data']['chatModel']->magnification),
            'type'=>'reducing',
            'created_at'=>now(),
            'desc'=>'对话提问消耗',
            'balance'=>$user->tokens+$total_token
        ]);
        HomeUserTokenRecords::create([
            'chat_id'=>$chat_id,
            'user_id'=>$request->input('user_id'),
            'amount'=>$total_token,
            'type'=>'reducing',
            'created_at'=>now(),
            'desc'=>'对话回复消耗',
            'balance'=>$user->tokens
        ]);
        }
        return response()->json(['code'=>200,'data'=>['reply_token'=>$total_token,'prompt_token'=>intval(strlen($content)*$getChatConfig['data']['chatModel']->magnification)]]);

    }

    /**
     * @param $chat_id
     * @param $client_id
     * @return array
     * @author:阿文
     * @date:2024/3/28 17:16
     */
    private function get_chat_config($chat_id,$client_id)
    {
        $chat = ChatItems::where('state','!=','删除')->find($chat_id);
        if (empty($chat)){
            return ['code'=>500,'message'=>'对话不存在'];
        }
        $chatModel = ChatModels::where('enabled',1)->find($chat->model_id);
        if (empty($chatModel)){
            return ['code'=>500,'message'=>'模型不存在'];
        }
        $role = ChatRole::find($chat->role_id);
        if (empty($role)){
            return ['code'=>500,'message'=>'角色不存在'];
        }
        $client = ChatClientIdLog::where('expiration_time','>',now())->find($client_id);
        if (empty($client)){
            return ['code'=>500,'message'=>'客户端不存在'];
        }
        $keyData = ChatApiKey::where('enabled',1)->find($chatModel->key_id);
        if (empty($keyData)){
            return ['code'=>500,'message'=>'apikey不存在'];
        }
        return ['code'=>200,'data'=>['chatModel'=>$chatModel,'role'=>$role,'keyData'=>$keyData,'client'=>$client,'chat'=>$chat]];
    }
    public function stop(request $request)
    {
        $stop_id = $request->input('stop_id');
        if (Cache::has($stop_id)){
            Cache::put($stop_id, 'off', 1);
        }
        return response()->json(['code'=>200,'message'=>'success']);
    }

    /**
     * @param ChatModels $chatModel
     * @param ChatApiKey $keyData
     * @param ChatRole $role
     * @param $msg
     * @param $client_id
     * @param $user_id
     * @param $role_id
     * @param $stop_id
     * @return int
     * @throws \Exception
     * @author:阿文
     * @date:2024/3/28 17:16
     */
    protected function sendMessage(ChatModels $chatModel, ChatApiKey $keyData, ChatRole $role,$msg,$client_id,$user_id,$role_id,$stop_id,$chat)
    {

        $messages=[];
        $count_token=0;
        if(!empty($role['context'])){
            $messages[]=['role'=>'system','content'=>$role['context']];
        }
        if ($chat->use_context==1){
            $messages=$this->getHistory($chat->id,$messages);
            $tokens = ChatHistory::where('chat_id',$chat->id)->where('type','reply')->orderBy('created_at','desc')->first();
            $prompt_tokens =  ChatHistory::where('chat_id',$chat->id)->where('type','prompt')->sum('tokens');
            empty($tokens)?$count_token=0:$count_token=$tokens->tokens;
        }
        $messages[]=['role'=>'user','content'=>$msg];
        $json = [
            'model'=>$chatModel['value'],
            'messages'=>$messages,
            'temperature'=>0.3,
            'max_tokens'=>4090
        ];


        $client = OpenAI::factory()
            ->withApiKey($keyData->value)
            ->withBaseUri($keyData->api_url)
            ->withHttpClient($client = new \GuzzleHttp\Client([]))
            ->withHttpHeader('Content-Type', 'application/json')
            ->withHttpHeader('Authorization', 'Bearer '.$keyData->value)
            ->withStreamHandler(fn (RequestInterface $request): ResponseInterface => $client->send($request, [
                'stream' => true
            ]))
            ->make();

        $result = $client->chat()->createStreamed($json);
        $content = '';
        Gateway::sendToClient($client_id,json_encode(['type'=>'Chat','data'=>['status'=>'Start','stop_id'=>$stop_id]]));
        foreach ( $result->getIterator() as $item){
       //     Gateway::sendToClient($client_id,json_encode(['type'=>cache::get($stop_id)]));
            if(cache::get($stop_id)!='on'){
                break;
            }
            Gateway::sendToClient($client_id,json_encode(['type'=>'Chat','data'=>['content'=>$item->choices[0]->delta->content,'status'=>'Replying']]));
            $content .= $item->choices[0]->delta->content;

        }
        Gateway::sendToClient($client_id,json_encode(['type'=>'Chat','data'=>['status'=>'End']]));
        if(empty($content)){
            return 0;
        }
        $totalTokens=intval(strlen($content)*$chatModel->magnification);
        ChatHistory::create([
            'chat_id'=>$chat->id,
            'user_id'=>$user_id,
            'role_id'=>$role_id,
            'content'=>$content,
            'type'=>'reply',
            'tokens'=>$totalTokens+$count_token,
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        return $totalTokens+$count_token;

    }

    /**
     * @param $chat_id
     * @param $messages
     * @return mixed
     * @author:阿文
     * @date:2024/3/28 17:16
     */
    private function getHistory($chat_id,$messages)
    {

        $history = ChatHistory::where('chat_id',$chat_id)->orderby('created_at')->get();
        foreach ($history as $item){
            if ($item->type=='prompt'){
                $messages[]=['role'=>'user','content'=>$item->content];
            }else{
                $messages[]=['role'=>'assistant','content'=>$item->content];
            }
        }

        return $messages;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/29 19:09
     */
    public function delete(request $request)
    {
        $chat_id = $request->input('chat_id');
        if (empty($chat_id)){
            return response()->json(['code'=>500,'message'=>'数据异常']);
        }
        $chat = ChatItems::where('state','正常')->where('user_id',$request->input('user_id'))->find($chat_id);
        if (empty($chat)){
            return response()->json(['code'=>500,'message'=>'对话不存在']);
        }
        $chat->state = '删除';
        $chat->deleted_at = now();
        $chat->save();
        return response()->json(['code'=>200,'message'=>'success']);
    }

}
