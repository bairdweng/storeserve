<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use DB;

class StoreController extends Controller
{


    public function ApplyToBeAbusinessMan(Request $request)
    {
        $allData = $request->all();
        $value = $request->cookie('token');
        $info = DB::table('st_store')->where('admind_id', '=', $value)->first();
        $createtime = date("Y-m-d H:i:s");
        if ($info) {
            return STJsonResultError("不能重复申请成为商家");
        } else {
            DB::table('st_store')->insert(
                [
                    'name' => $allData['name'],
                    'admind_id' => $value,
                    'introduction' => $allData['introduction'],
                    'store_type' => $allData['store_type'],
                    'address' => $allData['address'],
                    'createtime' => $createtime
                ]
            );
            return STJsonResultData(["info" => '申请成功，请耐心等待审核']);
        }
    }

    public function GetMyStoreInfo(Request $request)
    {
        $value = $request->cookie('token');
        $info = DB::table('st_store')->where('admind_id', '=', $value)->first();
        $info->icon_url = StoreIconAbsolutePath() . $info->icon_name;
        return STJsonResultData($info);
    }

    public function UpdateInfo(Request $request)
    {
        $att = self::getUpdateObject('st_store', $request->all());
        $uid = $request->cookie('token');
        if (count($att) > 0) {
            DB::table('st_store')->where('admind_id', $uid)->update(
                $att);
            return STJsonResultData(['info' => '成功']);
        } else {
            return STJsonResultError("没有可修改数据");
        }
    }

    public function getUpdateObject($tableName, $data)
    {
        $columns = Schema::getColumnListing($tableName);
        $att = array();
        foreach ($columns as $key) {
            if (array_key_exists($key, $data)) {
                if ($key != 'admind_id' && $key != 'icon_name' && $key != 'state' && $key != 'creattime' && $key != 'id') {
                    $att[$key] = $data[$key];
                }
            }
        }
        return $att;
    }

    public function GetStoreList(Request $request)
    {
        $list = DB::table('st_store')->get();
        foreach ($list as $info) {
            $info->icon_url = StoreIconAbsolutePath() . $info->icon_name;
        }
        return STJsonResultData($list);
    }

    public function ChangeStroeState(Request $request)
    {
        $allData = $request->all();
        $state = $allData['state'];
        if ($state != 0 && $state != 1 && $state != -1) {
            return STJsonResultError('state错误');
        }
        $info = DB::table('st_store')->where('id', '=', $allData['id'])->first();
        if ($info) {
            DB::table('st_store')->where('id', $allData['id'])->update(['state' => $state]);
            return STJsonResultData(['info' => '修改成功']);
        } else {
            return STJsonResultError('店铺不存在');
        }
    }

    public function UploadStoreIcon(Request $request)
    {
        $file = $request->file('img');
        $value = $request->cookie('token');
        if (empty($file)) {
            return STJsonResultError('img为空');
        }
        if (!$file->isValid()) {
            return STJsonResultError('文件上传失败');
        }
        $info = DB::table('st_store')->where('admind_id', '=', $value)->first();
        if ($info) {
            $icon_name = $info->icon_name;
            if ($icon_name) {
                STDeleteStoreIconByFileName($icon_name);
            }
            $newFileName = 'storeicon_' . md5(time() . rand(0, 10000)) . '.' . $file->getClientOriginalExtension();
            $savePath = StoreIconRelativePath();
            $file->move($savePath, $newFileName);
            DB::table('st_store')->where('admind_id', $value)->update(['icon_name' => $newFileName]);
            return STJsonResultData(['info' => '上传成功']);
        } else {
            return STJsonResultError('对不起，该用户不是商家');
        }
    }
}
