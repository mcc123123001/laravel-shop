<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfEmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()->email_verified) {
            // 如果是 AJAX 请求，则通过 JSON 返回
            //如果没有这段代码，当一个没有验证邮箱的用户使用 AJAX 请求接口时，
            //那么返回的是一个 302 跳转，302 跳转对于 AJAX 请求来说并不会让浏览器本身跳转到新页面，
            //那就导致用户看不到错误提示
            if ($request->expectsJson()) {
                return response()->json(['msg' => '请先验证邮箱'], 400);
            }
            return redirect(route('email_verify_notice'));
        }
        return $next($request);
    }
}
