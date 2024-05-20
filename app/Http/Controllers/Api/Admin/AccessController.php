<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\AdminUserAccess;
use App\Models\OperatorLog;

class AccessController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/1/9 16:49
     */
    public function list()
    {
        $list = AdminUserAccess::with('children')->where('parent_id',0)->get();
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
     * @date:2024/1/9 16:49
     */
    public function create(request $request)
    {

        $data = [];
        $data['name']=$request->post('name');
        $data['description']=$request->post('description','空');
        $data['code']=$request->post('code');
        $data['url']=$request->post('url');
        $data['parent_id']=$request->post('parent_id',0);
        $data['updated_at']=date('Y-m-d H:i:s');
        try {
            $re = AdminUserAccess::create($data);
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'添加失败,请检测信息'
            ];
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
     * @date:2024/1/9 18:24
     */
    public function update(request $request)
    {
        $data = AdminUserAccess::find($request->input('id'));

        $data->name = $request->input('name',$data->name);
        $data->url = $request->input('url',$data->url);
        $data->code = $request->input('code',$data->code);
        $data->description = $request->input('description',$data->description);
        $data->updated_at = date('Y-m-d H:i:s');

        try {
            $re = $data->Save();
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'修改失败,请检测信息'
            ];
           // $remark = "添加节点信息出错,";
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
     * @date:2024/1/9 18:24
     */
    public function delete(request $request)
    {
        $id = $request->input('id');

        try {
            AdminUserAccess::where('id',$id)->delete();
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
