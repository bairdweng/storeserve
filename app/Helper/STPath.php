<?php
/**
 * Created by PhpStorm.
 * User: baird
 * Date: 2017/9/19
 * Time: 下午3:01
 */
function ProductImgRelativePath()
{
    return 'Image/ProductImg/';
}
function ProductImgAbsolutePath()
{
    return 'http://' . $_SERVER['HTTP_HOST'] .'/store/public/'. ProductImgRelativePath();
}