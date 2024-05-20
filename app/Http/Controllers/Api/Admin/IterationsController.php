<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iterations;

class IterationsController extends Controller
{

    public function list(request $request)
    {

        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $list  = Iterations::paginate($page_size,['*'],'page',$page)->toArray();

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

    public function create(request $request)
    {
        $data = [];
        $data['version']=$request->post('version');
        $data['date']=$request->post('date');
        $data['title']=$request->post('title');
        $data['content']=$request->post('content');
        $data['modules']=$request->post('modules');
        $data['status']=$request->post('status');
        $data['persons']=$request->post('persons');
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        try {
            $re = Iterations::create($data);
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'创建页面失败'
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


    public function update(request $request)
    {
        $data = Iterations::find($request->input('id'));
        $data->version = $request->input('version',$data->version);
        $data->title = $request->input('title',$data->title);
        $data->date = $request->input('date',$data->date);
        $data->modules = $request->input('modules',$data->modules);
        $data->content = $request->input('content',$data->content);
        $data->persons = $request->input('persons',$data->persons);
        $data->status = $request->input('status',$data->status);
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


        Iterations::where('id',$id)->delete();


        $data = [
            'code'=>200,
            'message'=>'删除成功'
        ];
        return Response()->json($data);
    }


    public function details(request $request)
    {

        $id = $request->input('id');

        $data= Iterations::find($id);

        $data =[
            'code'=>200,
            'message'=>'',
            'data'=>$data
        ];
        return  response()->json($data);
    }
}
