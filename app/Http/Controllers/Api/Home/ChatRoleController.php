<?php

namespace App\Http\Controllers\api\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Models\ChatRole;
use App\Models\HomeUserToken;
use App\Models\UserLikeRole;
use Illuminate\Http\Request;

class ChatRoleController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/14 12:25
     */
    public function role_list(request $request)
    {
        //先判断是否是登陆状态
        $page = $request->input('page',1);
        $page_size = $request->input('page_size',10);
        $tag_id  =$request->input('tag_id',null);
        $search  =$request->input('search',null);
        $where=[];
        if (!empty($tag_id)){
            $where[] = ['tag_id','=',$tag_id];
        }
        if (!empty($search)){
            $where[] = ['name','like','%'.$search.'%'];
            $where[] = ['desc','like','%'.$search.'%'];
        }
        //判断是否登陆
        $is_login = false;
        if ($request->bearerToken()){
            $tokenData = HomeUserToken::where('token', $request->bearerToken())->where('expiration_time', '>', now())->where('state',1)->first(['user_id']);

            if ($tokenData) {
                $user_role_list=UserLikeRole::where('user_id',$tokenData['user_id'])->where('state','正常')->pluck('role_id')->toArray();
                $is_login =true;
            }
        }

        $list = ChatRole::select('id','icon','name','desc','hello_msg','suggestions','user_id')->with('user:id,nickname,avatar')->where('id','!=','system')->where('is_delete',0)->where($where)->where('enabled',1)->Orderby('sort_num','desc')->orderby('created_at')->paginate($page_size,['*'],'page',$page)->toArray();
        foreach ($list['data'] as $k=>$v){
            if ($is_login){
                $list['data'][$k]['is_like']=in_array($v['id'],$user_role_list);
            }else{
                $list['data'][$k]['is_like']=false;
            }
        }
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
     * @date:2024/4/21 17:12
     */
    public function role(request $request)
    {
        $id = $request->input('id');
        $data=ChatRole::where('is_delete',0)->find($id);
        $return = [
            'code'=>200,
            'data'=>$data
        ];
        if ($data->enabled!=1){
            if ($request->bearerToken()){

                $user_id = HomeUserToken::where('token', $request->bearerToken())->where('expiration_time', '>', now())->where('state',1)->value('user_id');
                if($user_id==$data->user_id){
                    return response()->json($return);
                }
            }
            return response()->json(['code'=>404]);
        }

        return response()->json($return);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/14 18:04
     */
    public function role_like(request $request)
    {
        if (empty($request->input('role_id'))){
            return response()->json(['code'=>500,'message'=>'数据错误']);
        }
        $exists  = ChatRole::where('is_delete',0)->where('enabled',1)->find($request->input('role_id'));
        if (!$exists){
            return response()->json(['code'=>500,'message'=>'数据错误']);
        }
        $data = [
            'role_id'=>$request->input('role_id'),
            'state'=>'正常',
            'user_id'=>$request->input('user_id')
        ];
        $likeLog=UserLikeRole::where($data)->first();
        if ($likeLog){
            $likeLog->state='删除';
            $likeLog->delete_at=now();
            $likeLog->save();
            $return = [
                'code'=>200,
                'message'=>'取消收藏'
            ];
            return response()->json($return);
        }
        $data['created_at'] = now();
        $data['sort_num']=0;
        try {
            UserLikeRole::create($data);
        }catch (\Exception $e){

            $data = [
                'code'=>500,
                'message'=>'收藏失败'
            ];
            return Response()->json($data);
        }
        $return = [
            'code'=>200,
            'message'=>'收藏成功'
        ];
        return response()->json($return);
    }

    /**
     * @param CreateRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/30 21:54
     */
    public function create_role(CreateRoleRequest $request)
    {
        $data = $request->all();
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        $data['user_id']=$request->input('user_id');
        try {
            $createData=ChatRole::create($data);
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'创建失败,请检测信息'
            ];
            return Response()->json($data);
        }

        $return = [
            'code'=>200,
            'message'=>'创建成功',
            'data'=>['id'=>$createData['id']]
        ];
        return response()->json($return);
    }

    /**
     * @param CreateRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/30 21:57
     */
    public function update_role(CreateRoleRequest $request)
    {

        $data = $request->all();
        $model = ChatRole::where('is_delete',0)->find($data['id']);
        if (empty($model)){
            return ;
        }
        $data['updated_at']=date('Y-m-d H:i:s');
        $data['user_id']=$request->input('user_id');

        try {
            $model->update($data);
        }catch (\Exception $e){
            $data = [
                'code'=>500,
                'message'=>'修改失败,请检测信息'
            ];
            return Response()->json($data);
        }

        $return = [
            'code'=>200,
            'message'=>'编辑成功'
        ];
        return response()->json($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/31 0:20
     */
    public function  delete_role(request $request)
    {
        //查询当前用户并且是传递id的role是否存在 不存在则数据异常
        $exists  = ChatRole::where('user_id',$request->input('user_id'))->find($request->input('id'));
        if (empty($exists)){
            return response()->json(['code'=>500,'message'=>'参数错误']);
        }
        $exists->is_delete=1;
        $exists->enabled=0;
        $exists->updated_at=now();
        $exists->save();
        $return = [
            'code'=>200,
            'message'=>'删除成功'
        ];
        return response()->json($return);
    }
}
