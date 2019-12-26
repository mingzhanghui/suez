<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 12/17/2019
 * Time: 16:48
 */

namespace app\controller;


use think\Controller;

class Study extends Controller
{
    public function index()
    {
        return "study";
    }

    /**
     * http://192.168.4.157:8058/study/testStudy/name/hello
     * @param $name
     * @return string
     */
    public function testStudy($name)
    {
        return "testStudy +++ " . $name;
    }
}