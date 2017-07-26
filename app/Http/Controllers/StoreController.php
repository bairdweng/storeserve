<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use DB;

class StoreController extends Controller
{
    public function Details(Request $request)
    {
        $uid = $request->cookie('token');
        $user = DB::table('st_store')->where('id', '=', $uid)->first();
        return response()->json(['data' => $user]);
    }

    public function UpdateInfo(Request $request)
    {
        $att = self::getUpdateObject('st_store', $request->all());
        $uid = $request->cookie('token');
        DB::table('st_store')->where('id', $uid)->update(
            $att);
        return response()->json(['result' => '1']);
    }

    public function getUpdateObject($tableName, $data)
    {
        $columns = Schema::getColumnListing($tableName);
        $att = array();
        foreach ($columns as $key) {
            if (array_key_exists($key, $data)) {
                $att[$key] = $data[$key];
            }
        }
        return $att;
    }
}
