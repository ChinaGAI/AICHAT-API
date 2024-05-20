<?php

namespace App\Http\Middleware;

use App\Models\AdminUserAccess;
use Closure;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use App\Models\AdminUserRole;
use App\Models\AdminUserToken;
use App\Models\OperatorLog as Opermodel;

class TokenMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     * @author:阿文
     * @date:2024/4/25 14:21
     */
    public function handle(Request $request, Closure $next)
    {

        $request_method = $request->getMethod();

        $token = $request->bearerToken();
        if (empty($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        //验证俩次token 是否过期 用户角色 是否有当前权限
        $where = [
            ['state','=',1],
            ['token','=',$token],
            ['expiration_time','>',time()]
        ];

        $user_token = AdminUserToken::where($where)->first(['state','expiration_time','user_id']);

        if(empty($user_token)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $url = \request()->getPathInfo().'.'.strtolower($request_method);

        $url_id = AdminUserAccess::where('url',$url)->select(['id'])->first();
//        if(empty($url_id)){
//            return response()->json(['error' => 'Unauthorized'], 401);
//        }
        $model = new AdminUser();
        $role_Data = $model->getRolodata($user_token['user_id']);
        $roles = $role_Data['access_ids'];

        if (!in_array($url_id['id'],explode(',',$roles))){
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        Opermodel::create(['user_id'=>$user_token['user_id'],'remark'=>'管理员id:'.$user_token['user_id'].'访问了'.$url,'ip'=>$request->getClientIp(),'request_data'=>json_encode($request->all(),JSON_FORCE_OBJECT)]);
        $request->merge(['current_user_id' => $user_token['user_id'],'role'=>$role_Data['role_id']]);

        return $next($request);
    }
}
