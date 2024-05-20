<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatRoleTag;
use App\Models\ChatRole;

class ChatRoleTagController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/14 16:32
     */
    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $list = ChatRoleTag::orderby('sort_num','desc')->get();
        $data =[
            'code'=>200,
            'message'=>'',
            'data'=> $list
        ];
        return  response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/14 16:32
     */
    public function create(request $request)
    {
        $data = [];
        $data['name']=$request->post('name');
        $data['icon']=$request->post('icon');
        $data['sort_num']=$request->post('sort_num');
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        try {
            ChatRoleTag::create($data);
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'添加失败,请检测信息'
            ];
            return Response()->json($data);
        }

        $return = [
            'code'=>200,
            'message'=>'创建成功'
        ];
        return response()->json($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/14 16:32
     */
    public function update(request $request)
    {
        $data = ChatRoleTag::find($request->input('id'));
        $data->name = $request->input('name',$data->name);
        $data->icon = $request->input('icon',$data->icon);
        $data->sort_num = $request->input('sort_num',$data->sort_num);
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
     * @date:2024/3/14 16:32
     */
    public function delete(request $request)
    {
        $id = $request->input('id');
        $states=ChatRole::where('tag_id',$id)->exists();
        if ($states){
            return Response()->json(['code'=>500,'message'=>'该分类下还有角色，请先转移/清楚 角色数据']);
        }
        try {
            ChatRoleTag::where('id',$id)->delete();
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
     * @date:2024/3/30 21:34
     */
    public function sort(request $request)
    {
        $sort =  $request->input('sort');

        foreach ($sort as $v){
            ChatRoleTag::where('id',$v['id'])->update(['sort_num'=>$v['sort']]);
        }

        $return = [
            'code'=>200,
            'message'=>'修改成功',
            'data'=>[]
        ];
        return response()->json($return);
    }
}
