<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUserRole;
use App\Models\OperatorLog;
class RoleController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/1/10 14:34
     */
    public function list()
    {
        $list = AdminUserRole::get();
        $return = [
            'code'=>200,
            'message'=>'',
            'data'=>$list
        ];
        return response()->json($return);
    }

    /**
     * @return void
     * @author:阿文
     * @date:2024/1/10 14:35
     */
    public function create(request $request)
    {

        $data = [];
        $data['role_name']=$request->input('role_name');
        $data['access_codes']=$request->post('access_codes');
        $data['access_ids']=$request->post('access_ids');
        $data['updated_at']=date('Y-m-d H:i:s');

        try {
            $re = AdminUserRole::create($data);
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'添加失败,请检测信息'
            ];
            $remark = "添加节点信息出错,";
          //  OperatorLog::create(['user_id'=>$request->input('user_id'),'reamark'=>$remark]);
            return Response()->json($data);
        }

        !empty($re)?$code=200:$code=500;
        !empty($re)?$messge='创建成功':$code='创建失败';
        $return = [
            'code'=>$code,
            'message'=>$messge,
            'data'=>[]
        ];
        return response()->json($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/1/10 15:56
     */
    public function update(request $request)
    {

        $data = AdminUserRole::find($request->input('id'));

        $data->role_name = $request->input('role_name',$data->role_name);
        $data->access_codes = $request->input('access_codes',$data->access_codes);
        $data->access_ids = $request->input('access_ids',$data->access_ids);
        $data->updated_at = date('Y-m-d H:i:s');

        try {
            $re = $data->Save();
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'修改失败,请检测信息'
            ];
            $remark = "添加节点信息出错,";
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/1/10 15:57
     */
    public function delete(request $request)
    {
        $id = $request->input('id');

        try {
            AdminUserRole::where('id',$id)->delete();
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'删除失败'
            ];
            $remark = "用户删除信息,";
            //  OperatorLog::create(['user_id'=>1,'reamark'=>$remark]);
            return Response()->json($data);
        }
        $data = [
            'code'=>200,
            'message'=>'删除成功'
        ];
        return Response()->json($data);
    }
}
