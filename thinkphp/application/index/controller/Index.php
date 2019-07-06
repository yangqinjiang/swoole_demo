<?php
namespace app\index\controller;
class Index
{
    public function index()
    {
        return  'call Index/index';
    }

    public function singwa() {
        echo time();
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 'hessdggsg' . $name.time();
    }

}
