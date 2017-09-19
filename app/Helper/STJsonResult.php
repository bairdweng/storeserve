<?php
/**
 * Created by PhpStorm.
 * User: baird
 * Date: 2017/9/19
 * Time: 下午3:23
 */
function STJsonResultData($arg1)
{
    return response()->json(['Data' => $arg1, 'state' => 1]);
}
function STJsonResultError($arg1)
{
    return response()->json(['info' => $arg1, 'state' => 0]);
}