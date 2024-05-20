<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeShop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/22 16:31
     */
    public function list(request $request)
    {
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $list = HomeShop::orderby('created_at','desc')->paginate($page_size,['*'],'page',$page)->toArray();
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
     * @date:2024/3/22 16:36
     */
    public function create(request $request)
    {
        $data = [];
        $data['title']=$request->post('title');
        $data['content']=$request->post('content');
        $data['tokens']=$request->post('tokens',0);
        $data['price']=$request->post('price');
        $data['desc']=$request->post('desc');
        $data['enable']=$request->post('enable',0);
        $data['origin_price']=$request->post('origin_price');
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        try {
            HomeShop::create($data);
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
        return Response()->json($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/22 16:45
     */
    public function update(request $request)
    {
        $data = HomeShop::find($request->input('id'));
        $data->title = $request->input('title',$data->title);
        $data->content=$request->input('content',$data->content);
        $data->tokens=$request->input('tokens',$data->tokens);
        $data->price=$request->input('price',$data->price);
        $data->desc=$request->input('desc',$data->desc);
        $data->enable=$request->input('enable',$data->enable);
        $data->origin_price=$request->input('origin_price',$data->origin_price);
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

    /**查询商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/22 17:29
     */
    public function shop(request $request)
    {
        $data = HomeShop::find($request->input('id'));
        $return = [
            'code'=>200,
            'message'=>'',
            'data'=>$data
        ];
        return response()->json($return);
    }
}
