<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatApiKey;

class ChatApiKeyController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/14 14:41
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

        $list = ChatApiKey::orderBy('sort_num','desc')->where($where)->paginate($page_size,['*'],'page',$page)->toArray();

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
     * @date:2024/3/14 14:47
     */
    public function create(request $request)
    {
        $data = [];
        $data['platform']=$request->post('platform');
        $data['name']=$request->post('name');
        $data['value']=$request->post('value');
        $data['enabled']=$request->post('enabled',1);
        $data['desc']=$request->post('desc');
        $data['api_url']=$request->post('api_url');
        $data['type']=$request->post('type');
        $data['icon']=$request->post('icon');
        $data['use_proxy']=$request->post('use_proxy');
        $data['sort_num']=$request->post('sort_num');
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        try {
            ChatApiKey::create($data);
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
     * @date:2024/3/14 14:47
     */
    public function update(request $request)
    {
        $data = ChatApiKey::find($request->input('id'));
        $data->platform = $request->input('platform',$data->platform);
        $data->name = $request->input('name',$data->name);
        $data->value = $request->input('value',$data->value);
        $data->type = $request->input('type',$data->type);
        $data->desc = $request->input('desc',$data->desc);
        $data->api_url = $request->input('api_url',$data->api_url);
        $data->enabled = $request->input('enabled',$data->enabled);
        $data->icon = $request->input('icon',$data->icon);
        $data->use_proxy = $request->input('use_proxy',$data->use_proxy);
        $data->sort_num = $request->input('sort_num',$data->sort_num);
        $data->updated_at = date('Y-m-d H:i:s');
        try {
            $data->Save();
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'修改失败,请检测信息'.$e->getMessage()
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
     * @date:2024/3/25 16:46
     */
    public function sort(request $request)
    {
        $sort =  $request->input('sort');
        foreach ($sort as $v){
            ChatApiKey::where('id',$v['id'])->update(['sort_num'=>$v['sort']]);
        }

        $return = [
            'code'=>200,
            'message'=>'修改成功',
            'data'=>[]
        ];
        return response()->json($return);
    }

}
