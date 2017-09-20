<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use DB;

class ProductController extends Controller
{
    public function AddAndUpdateProductInfo(Request $request)
    {
        $allData = $request->all();
        $product_id = $allData['product_id'];
        if ($product_id) {
            $info = DB::table('st_product_info')->where('product_id', '=', $product_id)->first();
            if ($info) {
                $att = self::getUpdateObject('st_product_info', $allData);
                $att['lasttime'] = date("Y-m-d H:i:s");
                DB::table('st_product_info')->where('product_id', $product_id)->update(
                    $att);
                return STJsonResultData(['info' => '成功']);
            } else {
                return STJsonResultError(['info' => '产品不存在，无法修改']);
            }
        } else {
            $store_id = $allData['store_id'];
            $product_name = $allData['product_name'];
            $product_price = $allData['product_price'];
            $product_details = $allData['product_details'];
            $createtime = date("Y-m-d H:i:s");
            DB::table('st_product_info')->insert(
                [
                    'store_id' => $store_id,
                    'product_name' => $product_name,
                    'product_price' => $product_price,
                    'product_details' => $product_details,
                    'createtime' => $createtime,
                    'lasttime' => $createtime
                ]
            );
            return STJsonResultData(['info' => '成功']);
        }
    }

    public function UpLoadProductImg(Request $request)
    {
        $file = $request->file('img');
        $allData = $request->all();
        $product_id = $allData['product_id'];
        if (empty($file)) {
            return STJsonResultError('img为空');
        }
        if (!$file->isValid()) {
            return STJsonResultError('文件上传失败');
        }
        $st_product_info = DB::table('st_product_info')->where('product_id', $product_id)->first();
        $createtime = date("Y-m-d H:i:s");
        if (!$st_product_info) {
            return STJsonResultError('product_id不存在');

        } else {
            $newFileName = md5(time() . rand(0, 10000)) . '.' . $file->getClientOriginalExtension();
            $savePath = ProductImgRelativePath();
            $file->move($savePath, $newFileName);
            DB::table('st_product_img')->insert(
                [
                    'product_id' => $product_id,
                    'img_name' => $newFileName,
                    'createtime' => $createtime
                ]
            );
            return STJsonResultData(['info' => '上传成功']);
        }
    }

    public function GetProductByStoreId(Request $request)
    {
        $allData = $request->all();
        $store_id = $allData['store_id'];
        $infoList = DB::table('st_product_info')->where('store_id', $store_id)->get();
        $productInfos = array();
        for ($i = 0; $i < count($infoList); $i++) {
            $info = $infoList[$i];
            $imgs = DB::table('st_product_img')->where('product_id', $info->product_id)->get();
            $info->imgs = self::ToExtractTheImage($imgs);
            $productInfos[$i] = $info;
        }
        return STJsonResultData($productInfos);
    }

    public function GetProductByProductId(Request $request)
    {
        $allData = $request->all();
        $product_id = $allData['product_id'];
        $info = DB::table('st_product_info')->where('product_id', $product_id)->first();
        if ($info) {
            $imgs = DB::table('st_product_img')->where('product_id', $info->product_id)->get();
            $info->imgs = self::ToExtractTheImage($imgs);
            return STJsonResultData($info);
        } else {
            return STJsonResultError(['产品不存在']);
        }
    }

    public function DeleteProductById(Request $request)
    {
        $allData = $request->all();
        $product_id = $allData['product_id'];
        $result = DB::table('st_product_info')->where('product_id', $product_id)->delete();
        if ($result == 1) {
            $imgs = DB::table('st_product_img')->where('product_id', $product_id)->get();
            foreach ($imgs as $img) {
                $img_name = $img->img_name;
                STDeleteImgByFileName($img_name);
            }
            DB::table('st_product_img')->where('product_id', $product_id)->delete();
            return STJsonResultData(['info' => '删除成功']);
        } else {
            return STJsonResultError('删除失败');
        }
    }

    public function DeleteProductImgById(Request $request)
    {
        $allData = $request->all();
        $AutoId = $allData['AutoId'];
        $result = DB::table('st_product_img')->where('AutoId', $AutoId)->first();
        if ($result) {
            $img_name = $result->img_name;
            STDeleteImgByFileName($img_name);
            DB::table('st_product_img')->where('AutoId', $AutoId)->delete();
            return STJsonResultData(['info' => '删除成功']);
        } else {
            return STJsonResultError('删除失败');
        }
    }

    public function ToExtractTheImage($lists)
    {
        $imgs = array();
        for ($i = 0; $i < count($lists); $i++) {
            $imgObject = $lists[$i];
            $img_name = ProductImgAbsolutePath() . $imgObject->img_name;
            $imgs[$i] = ['URL' => $img_name, 'AutoId' => $imgObject->AutoId];
        }
        return $imgs;
    }

    public function getUpdateObject($tableName, $data)
    {
        $columns = Schema::getColumnListing($tableName);
        $att = array();
        foreach ($columns as $key) {
            if (array_key_exists($key, $data)) {
                if ($key != 'createtime') {
                    $att[$key] = $data[$key];
                }
            }
        }
        return $att;
    }
}
