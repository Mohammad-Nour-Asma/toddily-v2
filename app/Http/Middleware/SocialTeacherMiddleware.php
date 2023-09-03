<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SocialTeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Role::find($request->user()->role_id)->role_name == 'teacher' || Role::find($request->user()->role_id)->role_name == 'social' ) {
            return $next($request);
        }
        return  \response(['message'=>'Unauthorized'  ], 401);
    }
}
