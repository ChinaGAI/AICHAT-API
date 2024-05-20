<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatModels;

class ChatModelsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/13 23:24
     */
    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $name  =$request->input('name',null);
        $where = [];

        if (!empty($name)){
            $where[] = ['name','like','%'.$name.'%'];
        }

        $list = ChatModels::orderBy('sort_num','desc')->orderby('created_at','desc')->where($where)->paginate($page_size,['*'],'page',$page)->toArray();

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
     * @date:2024/3/13 23:30
     */
    public function create(request $request)
    {
        $data = [];
        $data['key_id']=$request->post('key_id');
        $data['name']=$request->post('name');
        $data['value']=$request->post('value');
        $data['sort_num']=$request->post('sort_num',0);
        $data['enabled']=$request->post('enabled',1);
        $data['magnification']=$request->post('magnification',1);
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        try {
          $model =    ChatModels::create($data);
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'添加失败,请检测信息'
            ];
            return Response()->json($data);
        }

        $return = [
            'code'=>200,
            'message'=>'创建成功',
            'data'=>['id'=>$model->id]
        ];
        return response()->json($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/13 23:34
     */
    public function update(request $request)
    {
        $data = ChatModels::find($request->input('id'));
        $data->key_id = $request->input('key_id',$data->key_id);
        $data->value = $request->input('value',$data->value);
        $data->name = $request->input('name',$data->name);
        $data->sort_num = $request->input('code',$data->sort_num);
        $data->enabled = $request->input('enabled',$data->enabled);
        $data->magnification = $request->input('magnification',$data->magnification);
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
     * @date:2024/3/13 23:35
     */
    public function delete(request $request)
    {
        $id = $request->input('id');

        try {
            ChatModels::where('id',$id)->delete();
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'删除失败'
            ];
            return Response()->json($data);
        }
        $data = [
            'code'=>200,
            'message'=>'删除成功'
        ];
        return Response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/25 16:58
     */
    public function sort(request $request)
    {
        $sort =  $request->input('sort');

        foreach ($sort as $v){
            ChatModels::where('id',$v['id'])->update(['sort_num'=>$v['sort']]);
        }

        $return = [
            'code'=>200,
            'message'=>'修改成功',
            'data'=>[]
        ];
        return response()->json($return);
    }
}
