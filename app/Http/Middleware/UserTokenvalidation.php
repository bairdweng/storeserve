<?php
/**
 * Created by PhpStorm.
 * User: Baird
 * Date: 16/10/11
 * Time: 下午2:32
 */

namespace App\Http\Middleware;

use Closure, Request, DB;

//验证token是否正确,否则无法访问
class usertokenvalidation
{
    public function handle($request, Closure $next)
    {
        return self::VerifyuseToken($request, $next);
    }

    /* 验证token。
     * */
    public function VerifyuseToken($request, $next)
    {
        $token = $request->cookie('token');
        if ($token) {
            return $next($request);
        } else {
            return response()->json(['error' => 'token错误，请重新登录']);
        }
    }
}


