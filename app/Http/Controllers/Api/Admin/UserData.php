<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use App\Models\AdminUserToken;
use App\Models\AdminUserRole;

class UserData extends Controller
{
    /**返回当前角色信息
     * @param Request $request
     * @return void
     * @author:阿文
     * @date:2024/1/8 15:46
     */
    public function    user_data(request $request)
    {

            $req = $request->header('Authorization');

            $token = substr($req,7);

            $user_id= AdminUserToken::where('token',$token)->firstOrFail();

            $user_model = new AdminUser();
            $data = $user_model->user_data_show($user_id['user_id']);

            $data['access_codes']=AdminUserRole::select('access_codes')->find($data['role_id'])['access_codes'];

            $return = [
                'code'=>200,
                'message'=>'',
                'data'=>$data
            ];

            return  response()->json($return);
    }

    /**
     * @return void
     * @author:阿文
     * @date:2024/1/11 17:48
     */
    public function update(request $request)
    {
        $usernmme = $request->input('username');
        $data = AdminUser::find($request->input('current_user_id'));

        if (!empty($usernmme)&&$usernmme!=$data['username']){
            $return=[
                'code'=>500,
                'message'=>'用户名已被占用,请更换'
            ];
            return  response()->json($return);
        }

        $data->username = $request->input('username',$data->username);
        $data->nickname = $request->input('nickname',$data->nickname);
        $data->phone_number = $request->input('phone_number',$data->phone_number);
        $data->updated_at = date('Y-m-d H:i:s');
        try {
            $re = $data->Save();
        }catch (\Exception $e){

            $data = [
                'code'=>500,
                'message'=>'修改失败,请检测信息'
            ];
            //   $remark = "添加节点信息出错,";
            //  OperatorLog::create(['user_id'=>1,'reamark'=>$remark]);
            return Response()->json($data);
        }

        $return = [
            'code'=>200,
            'message'=>'修改成功',
            'data'=>[]
        ];
        return response()->json($return);
    }

    /**
     * @return void
     * @author:阿文
     * @date:2024/1/11 17:48
     */
    public function update_pwd(request $request)
    {
        $pwd = $request->input('password',null);
        $new_pwd = $request->input('new_password',null);
        $return = [
            'code'=>500,
            'message'=>'',
            'data'=>[]
        ];
        if (empty($pwd)||empty($new_pwd)){
            $return['message']='信息异常';
            return  response()->json($return);
        }
        $data = AdminUser::find($request->input('current_user_id'));

        if(md5($pwd.env('OB_CODE'))!=$data['pwd']){
            $return['message']='原密码输入错误,请重试或联系负责人';
            return  response()->json($return);
        }
        if($pwd == $new_pwd){
            $return['message']='原密码和新密码一样,请重试';
            return  response()->json($return);
        }
        $data->pwd = md5($new_pwd.env('OB_CODE'));
        try {
            $re = $data->Save();
        }catch (\Exception $e){
            $return['message']='修改失败,请联系管理员';
            //   $remark = "添加节点信息出错,";
            //  OperatorLog::create(['user_id'=>1,'reamark'=>$remark]);
            return Response()->json($return);
        }

        $return['code']=200;
        $return['message']='修改密码成功';
        return response()->json($return);
    }
}
