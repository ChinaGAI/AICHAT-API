<?php
// app/Http/Middleware/VerifyToken.php
namespace App\Http\Middleware;

use Closure;
use App\Models\HomeUserToken;

class VerifyHomeTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken(); // 获取 Bearer Token

        if (!$token) {
            return response()->json(['error' => '未提供令牌'], 401);
        }

        $tokenData = HomeUserToken::where('token', $token)->where('expiration_time', '>', now())->where('state',1)->first(['user_id']);

        if (!$tokenData) {
            return response()->json(['error' => '令牌错误'], 401);
        }

        // Token 有效，将用户 ID 附加到请求中
        $request->merge(['user_id' => $tokenData->user_id]);

        return $next($request);
    }
}

