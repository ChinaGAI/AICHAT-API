<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatRole;
use Illuminate\Support\Str;

class ChatRoleController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/14 14:35
     */
    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $name  =$request->input('name',null);
        $tag_id = $request->input('tag_id',null);
        $user_id = $request->input('user_id',null);
        $is_delete = $request->input('is_delete',null);
        $where = [];
        if (!empty($name)){
            $where[] = ['name','like','%'.$name.'%'];
        }
        if (!empty($tag_id)){
            $where[] = ['tag_id','=',$tag_id];
        }
        if (!empty($user_id)){
            $where[] = ['user_id','=',$user_id];
        }
        if(!is_null($is_delete)){
            $where[] = ['is_delete','=',$is_delete];
        }
        $list = ChatRole::with(['tag','user'])->orderBy('created_at')->where($where)->paginate($page_size,['*'],'page',$page)->toArray();

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
     * @date:2024/3/14 14:35
     */
    public function create(request $request)
    {
        $data = [];
        $data['name']=$request->post('name');
        $data['context']=$request->post('context');
        $data['desc']=$request->post('desc');
        $data['hello_msg']=$request->post('hello_msg');
        $data['icon']=$request->post('icon');
        $data['tag_id']=$request->post('tag_id');
        $data['suggestions']=$request->post('suggestion');
        $data['enabled']=$request->post('enabled',1);
        $data['user_id']=$request->post('user_id','system');
        $data['sort_num']=$request->post('sort_num',0);
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        try {
            ChatRole::create($data);
        }catch (\Exception $e){

            $data = [
                'code'=>500,
                'message'=>'添加失败,请检测信息'
            ];
            return Response()->json($data);
        }


        $return = [
            'code'=>200,
            'message'=>'添加成功'
        ];
        return response()->json($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/14 14:35
     */
    public function update(request $request)
    {
        $data = ChatRole::find($request->input('id'));
        $data->name = $request->input('name',$data->name);
        $data->context = $request->input('context',$data->context);
        $data->desc = $request->input('desc',$data->desc);
        $data->icon = $request->input('icon',$data->icon);
        $data->enabled = $request->input('enabled',$data->enabled);
        $data->tag_id = $request->input('tag_id',$data->tag_id);
        $data->hello_msg = $request->input('hello_msg',$data->hello_msg);
        $data->suggestions = $request->input('suggestions',$data->suggestions);
        $data->sort_num = $request->input('sort_num',$data->sort_num);
        $data->user_id = $request->input('user_id',$data->user_id);
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
     * @date:2024/3/14 14:35
     */
    public function delete(request $request)
    {
        $id = $request->input('id');

        try {
            ChatRole::where('id',$id)->delete();
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
}
