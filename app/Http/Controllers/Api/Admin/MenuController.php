<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * @param Request $request
     * @return void
     * @author:阿文
     * @date:2024/1/13 18:21
     */
    public function list(request $request)
    {
        $p_id= $request->input('parent_id',0);
        $list = Menu::where('parent_id',$p_id)->orderby('sort','desc')->get();
        $return = [
            'code'=>200,
            'message'=>'',
            'data'=>$list
        ];
        return response()->json($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/1/13 18:26
     */
    public function sort(request $request)
    {
        $sort =  $request->input('sort');
        foreach ($sort as $v){
            Menu::where('id',$v['id'])->update(['sort'=>$v['sort']]);
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
     * @date:2024/1/13 18:29
     */
    public function create(request $request)
    {
        $data = [];
        $data['name']=$request->post('name');
        $data['parent_id']=$request->post('parent_id',0);
        $data['link']=$request->post('link');
        $data['sort']=$request->post('sort');
        $data['target_type']=$request->post('target_type');
        $data['updated_at']=date('Y-m-d H:i:s');
        try {
            $re = Menu::create($data);
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'创建tag失败,请检测信息'
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
     * @date:2024/1/13 18:32
     */
    public function update(request $request)
    {
        $data = Menu::find($request->input('id'));
        $data->name = $request->input('name',$data->name);
        $data->parent_id = $request->input('parent_id',$data->parent_id);
        $data->link = $request->input('link',$data->link);
        $data->sort = $request->input('sort',$data->sort);
        $data->target_type = $request->input('target_type',$data->target_type);
        $data->updated_at=date('Y-m-d H:i:s');
        try {
            $data->Save();
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
    public function delete(request $request)
    {
        $id = $request->input('id');
        $ids = DB::select("WITH RECURSIVE sub_ids AS ( SELECT id, parent_id  FROM home_menu WHERE ID = $id   UNION ALL SELECT t.ID, t.parent_id  FROM home_menu AS t  INNER JOIN sub_ids AS s ON t.parent_id = s.id) SELECT GROUP_CONCAT(ID SEPARATOR ',') as ids FROM sub_ids;")[0]->ids;

        $ids_arr = explode(',',$ids);

        if (!empty($ids)){
            Menu::wherein('id',$ids_arr)->delete();
        }else{
            $data =[
                'code'=>500,
                'message'=>'删除失败'
            ];
            return Response()->json($data);
        }


        $data = [
            'code'=>200,
            'message'=>'删除成功,已删除当前菜单以及下级菜单'
        ];
        return Response()->json($data);
    }
}
