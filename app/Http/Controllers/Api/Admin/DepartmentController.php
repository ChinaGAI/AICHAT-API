<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use App\Models\AdminUser;

class DepartmentController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/1/9 16:49
     */
    public function list()
    {
        $list = Department::with('children')->where('parent_id',0)->get();
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
        $data['leader']=$request->post('leader','暂无');
        $data['parent_id']=$request->post('parent_id',0);
        $data['updated_at']=date('Y-m-d H:i:s');
        try {
            $re = Department::create($data);
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'添加失败,请检测信息'
            ];
            $remark = "添加部门信息出错,";
            //OperatorLog::create(['user_id'=>$request->input('user_id'),'reamark'=>$remark]);
            return Response()->json($data);
        }

        !empty($re)?$code=200:$code=500;
        !empty($re)?$messge='创建部门成功':$code='创建部门失败';
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
        $data = Department::find($request->input('id'));

        $data->name = $request->input('name',$data->name);
        $data->leader = $request->input('leader',$data->url);
        $data->updated_at = date('Y-m-d H:i:s');

        try {
            $data->Save();
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'修改失败,请检测信息'
            ];

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
       // $list = Department::with('children')->where('parent_id',$id)->select(['id'])->get()->toArray();
        //$ids = Department::with('children')->where('parent_id',$id);
        $ids = DB::select("WITH RECURSIVE sub_ids AS ( SELECT id, parent_id  FROM admin_department WHERE ID = $id   UNION ALL SELECT t.ID, t.parent_id  FROM admin_department AS t  INNER JOIN sub_ids AS s ON t.parent_id = s.id) SELECT GROUP_CONCAT(ID SEPARATOR ',') as ids FROM sub_ids;")[0]->ids;

        $ids_arr = explode(',',$ids);

        $data = AdminUser::wherein('role_id',$ids_arr)->select('id')->first();
        if (empty($data)){
            Department::wherein('id',$ids_arr)->delete();
        }else{
           $data =[
               'code'=>500,
               'message'=>'当前部门或所有下级部门还有用户,请先完成转移'
           ];
            return Response()->json($data);
        }


        $data = [
            'code'=>200,
            'message'=>'删除成功,已删除当前部门以及下级部门'
        ];
        return Response()->json($data);
    }
}
