<?php
/**
 * Created by PhpStorm.
 * User: baird
 * Date: 2017/9/19
 * Time: 下午4:14
 */
function STDeleteImgByFileName($fileName)
{
    $path = ProductImgRelativePath() . $fileName;
    if (file_exists($path)) {
        unlink($path);
    }
}