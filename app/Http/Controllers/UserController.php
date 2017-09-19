<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    /*登录*/
    public function Login(Request $request)
    {
        $cookie = cookie('token', '1', 60 * 10);
        return STJsonResultData(['id' => 1])->cookie($cookie);
    }

    public function Details(Request $request)
    {
        $value = $request->cookie('token');
        $user = DB::table('st_user')->where('id', '=', $value)->first();
        return STJsonResultData($user);
    }
}
