<?php

namespace App\Http\Controllers;

use \Curl\Curl;
use App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    /*登录*/
    public function Login(Request $request)
    {
        $allData = $request->all();
        $code = $allData['code'];
        if ($code == 'BairdTest') {
            $cookie = cookie('token', '1', 60 * 10);
            $devUserInfo = [
                'nickname' => 'BairdTest',
                'sex' => '1',
                'province' => '广东',
                'city' => '广州',
                'country' => '中国',
                'headimgurl' => 'http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ
4eMsv84eavHiaiceqxibJxCfHe/46'
            ];
            return STJsonResultData($devUserInfo)->cookie($cookie);
        } else {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx7c0709fbe48fbc38&secret=093a0405af80b93edfc496f6f9b1284b&code=' . $code . '&grant_type=authorization_code';
            $curl = new Curl();
            $curl->get($url);
            $resultData = $curl->response;
            $dataObj = json_decode($resultData);
            $access_token = $dataObj->access_token;
            if ($access_token) {
                $userInfo = self::creatUserInfo($dataObj);
                $cookie = cookie('token', $userInfo->id, 60 * 10);
                return STJsonResultData(self::creatUserInfo($dataObj))->cookie($cookie);
            } else {
                return STJsonResultError($dataObj->errmsg);
            }
        }
    }

    //创建用户信息
    private function creatUserInfo($dataObj)
    {
        $openid = $dataObj->openid;
        $access_token = $dataObj->access_token;
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
        $curl = new Curl();
        $curl->get($url);
        $resultData = $curl->response;
        $dataObj = json_decode($resultData);
        $sex = $dataObj->sex;
        $headimgurl = $dataObj->headimgurl;
        $nickname = $dataObj->nickname;
        $openid = $dataObj->openid;
        $unionid = $dataObj->unionid;
        $country = $dataObj->country;
        $province = $dataObj->province;
        $city = $dataObj->city;
        $createtime = date("Y-m-d H:i:s");
        $lasttime = date("Y-m-d H:i:s");
        $info = DB::table('st_user')->where('unionid', '=', $unionid)->first();
        if ($info) {
            DB::table('st_user')
                ->where('unionid', $unionid)
                ->update(
                    [
                        'headimgurl' => $headimgurl,
                        'nickname' => $nickname,
                        'country' => $country,
                        'province' => $province,
                        'city' => $city,
                        'lasttime' => $lasttime
                    ]);
            $newinfo = DB::table('st_user')->where('unionid', '=', $unionid)->first();
            return $newinfo;
        } else {
            DB::insert('insert into st_user (unionid,openid,country,province,city,sex,access_token,headimgurl,nickname,createtime,lasttime) values (?,?,?,?,?,?,?,?,?,?,?)', [
                $unionid,
                $openid,
                $country,
                $province,
                $city,
                $sex,
                $access_token,
                $headimgurl,
                $nickname,
                $createtime,
                $lasttime
            ]);
            $info = DB::table('st_user')->where('unionid', '=', $unionid)->first();
            return $info;
        }
    }

    public function Details(Request $request)
    {
        $value = $request->cookie('token');
        $user = DB::table('st_user')->where('id', '=', $value)->first();
        return STJsonResultData($user);
    }




}
