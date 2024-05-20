<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/1/11 17:16
     */
    public function list(request $request)
    {
        //获取传递的参数
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $role_id   =$request->input('role_id',null);
        $department_id  =$request->input('department_id',null);
        $phone_number  =$request->input('phone_number',null);
        $nickname  =$request->input('nickname',null);
        $username  =$request->input('username',null);
        $where = [];

        if (!empty($nickname)){
            $where[] = ['user.nickname','like','%'.$nickname.'%'];
        }
        if (!empty($phone_number)){
            $where[] = ['user.phone_number','like','%'.$phone_number.'%'];
        }
        if (!empty($username)){
            $where[] = ['user.username','like','%'.$username.'%'];
        }
        if(!empty($department_id)){

            $ids = DB::select("WITH RECURSIVE sub_ids AS ( SELECT id, parent_id  FROM admin_department WHERE ID = $department_id   UNION ALL SELECT t.ID, t.parent_id  FROM admin_department AS t  INNER JOIN sub_ids AS s ON t.parent_id = s.id) SELECT GROUP_CONCAT(ID SEPARATOR ',') as ids FROM sub_ids;")[0]->ids;

            $where[] = [DB::raw("user.department_id in ({$ids})"),'1'];
        }

        if (!empty($role_id)){
            $where[] = ['role_id','=',$role_id];
        }
        $model = new AdminUser();

        $list = $model->getUserlst($where,$page_size,$page);

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
     * @date:2024/1/11 17:16
     */
    public function create(request $request)
    {

        $data = [];
        $data['phone_number']=$request->post('phone_number');
        $data['username']=$request->post('username');
        $data['nickname']=$request->post('nickname');
        $data['role_id']=$request->post('role_id');
        $data['department_id']=$request->post('department_id');
        $data['avatar']=$request->post('avatar');
        $data['updated_at']=date('Y-m-d H:i:s');
        $data['state']=1;
        $data['pwd'] = md5($data['phone_number'].env('OB_CODE'));

        try {
            $re = AdminUser::create($data);
        }catch (\Exception $e){

            $data = [
                'code'=>500,
                'message'=>'创建员工失败,请检测信息'
            ];
            // $remark = "添加节点信息出错,";
            // OperatorLog::create(['user_id'=>$request->input('user_id'),'reamark'=>$remark]);
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
     * @date:2024/1/11 17:16
     */
    public function update(request $request)
    {
        $data = AdminUser::find($request->input('id'));

        $data->username = $request->input('username',$data->username);
        $data->nickname = $request->input('nickname',$data->nickname);
        $data->role_id = $request->input('role_id',$data->role_id);
        $data->department_id = $request->input('department_id',$data->department_id);
        $data->avatar = $request->input('avatar',$data->avatar);
        $data->phone_number = $request->input('phone_number',$data->phone_number);
        $data->state = $request->input('state',$data->state);
        $data->updated_at = date('Y-m-d H:i:s');
        empty($request->input('password'))?:$data->pwd=md5($request->input('password').env('OB_CODE'));
        try {
            $re = $data->Save();
        }catch (\Exception $e){

            $data = [
                'code'=>500,
                'message'=>'用户名需要是唯一哟'
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
}
