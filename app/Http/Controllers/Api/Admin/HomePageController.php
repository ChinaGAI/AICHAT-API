<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomePage as PageModel;

class HomePageController extends Controller
{
    /**
     * @return void
     * @author:阿文
     * @date:2024/1/13 15:24
     */
    public function list(request $request)
    {

        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $list  = PageModel::paginate($page_size,['*'],'page',$page)->toArray();

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
     * @return void
     * @author:阿文
     * @date:2024/1/13 15:47
     */
    public function create(request $request)
    {
        $data = [];
        $data['alias']=$request->post('alias');
        $data['title']=$request->post('title');
        $data['desc']=$request->post('desc');
        $data['content']=$request->post('content');
        $data['time']=date('Y-m-d H:i:s');
        try {
            $re = PageModel::create($data);
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/1/13 15:52
     */
    public function update(request $request)
    {
        $data = PageModel::find($request->input('id'));
        $data->alias = $request->input('alias',$data->alias);
        $data->title = $request->input('title',$data->title);
        $data->desc = $request->input('desc',$data->desc);
        $data->content = $request->input('content',$data->content);
        $data->time=date('Y-m-d H:i:s');
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

    /**
     * @param Request $request
     * @return void
     * @author:阿文
     * @date:2024/1/13 15:52
     */
    public function delete(request $request)
    {
        $id = $request->input('id');


        PageModel::where('id',$id)->delete();


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
     * @date:2024/1/21 20:30
     */
    public function details(request $request)
    {

        $id = $request->input('id');

       $data= PageModel::find($id);

        $data =[
            'code'=>200,
            'message'=>'',
            'data'=>$data
        ];
        return  response()->json($data);
    }
}
