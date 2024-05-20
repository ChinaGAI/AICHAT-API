<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\HomeUserTokenRecords;
use Illuminate\Http\Request;
use App\Models\HomeUser;
use Mockery\Exception;
use App\Models\HomeUserToken;
use App\Models\Sms;

class HomeUserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/4/21 15:32
     */
    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $username  =$request->input('username',null);
        $email  =$request->input('email',null);
        $phone  =$request->input('phone',null);
        $where = [];
        if (!empty($username)){
            $where[] = ['username','like','%'.$username.'%'];
        }
        if (!empty($email)){
            $where[] = ['email','like','%'.$email.'%'];
        }
        if (!empty($phone)){
            $where[] = ['phone','like','%'.$phone.'%'];
        }
        $list = HomeUser::orderby('created_at','desc')->where($where)->paginate($page_size,['*'],'page',$page)->toArray();

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
     * @date:2024/4/21 16:37
     */
    public function add_token(request $request)
    {
        $user_id = $request->input('user_id');
        $tokens = intval($request->input('tokens'));
        $type   =$request->input('type');
        $user = HomeUser::find($user_id);
        if ($type=='add'){
            $user->tokens = $user->tokens+$tokens;
            $user->all_tokens = $user->all_tokens+$tokens;
        }else{
            $user->tokens = $user->tokens-$tokens;
            $user->all_tokens = $user->all_tokens-$tokens;
        }
        try {
            $user->save();
        }catch (Exception $e){
            $data = [
                'code'=>500,
                'message'=>'修改失败,请检测信息'
            ];

            return Response()->json($data);
        }
        HomeUserTokenRecords::create([
            'user_id'=>$request->input('user_id'),
            'amount'=>$tokens,
            'type'=>$type,
            'created_at'=>now(),
            'desc'=>'系统增加',
            'balance'=>$user->tokens
        ]);

        $return = [
            'code'=>200,
            'message'=>'修改token完成',
            'data'=>[]
        ];
        return response()->json($return);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/1/19 14:46
     */
    public  function sms_list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $phone   =$request->input('phone',null);
        $scene   =$request->input('scene',null);
        $ip   =$request->input('ip',null);
        $where=[];
        if(!empty($phone)){
            $where[] = ['phone','like','%'.$phone.'%'];
        }
        if(!empty($ip)){
            $where[] = ['ip','like','%'.$ip.'%'];
        }
        if(!empty($scene)){
            $where[] = ['scene','=',$scene];
        }
        $list=Sms::where($where)
            ->orderBy('created_at','desc')
            ->paginate($page_size,['*'],'page',$page)
            ->toArray();
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
    public  function email_list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $email   =$request->input('email',null);
        $scene   =$request->input('scene',null);
        $ip   =$request->input('ip',null);
        $where=[];
        if(!empty($email)){
            $where[] = ['email','like','%'.$email.'%'];
        }
        if(!empty($ip)){
            $where[] = ['ip','like','%'.$ip.'%'];
        }
        if(!empty($scene)){
            $where[] = ['scene','=',$scene];
        }
        $list=Email::where($where)
            ->orderBy('created_at','desc')
            ->paginate($page_size,['*'],'page',$page)
            ->toArray();
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
    public function token_list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $ip   =$request->input('ip',null);
        $user_id  =$request->input('user_id',null);
        $where=[];
        if(!empty($ip)){
            $where[] = ['ip','like','%'.$ip.'%'];
        }
        if(!empty($user_id)){
            $where[] = ['user_id','=',$user_id];
        }

        $list=HomeUserToken::with('user')
            ->where($where)
            ->orderBy('created_at','desc')
            ->paginate($page_size,['*'],'page',$page)
            ->toArray();
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
}
