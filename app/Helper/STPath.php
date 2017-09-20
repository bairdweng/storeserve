<?php
/**
 * Created by PhpStorm.
 * User: baird
 * Date: 2017/9/19
 * Time: 下午3:01
 */


$currentPath = $devPath;


function getHostPath()
{
    $devPath = 'http://' . $_SERVER['HTTP_HOST'] . '/store/public/';
//    $ProductPath = 'http://' . $_SERVER['HTTP_HOST'] . '/store/public/';
    return $devPath;
}

function ProductImgRelativePath()
{
    return 'Image/ProductImg/';
}

function StoreIconRelativePath()
{
    return 'Image/StoreIcon/';
}

function ProductImgAbsolutePath()
{
    return getHostPath() . ProductImgRelativePath();
}

function StoreIconAbsolutePath()
{
    return getHostPath() . StoreIconRelativePath();
}