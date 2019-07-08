<?php
namespace app\index\controller;
class Index
{
    // http://127.0.0.1:8811/?s=/index/index/index
    public function index()
    {
        return  "worker start ...".PHP_EOL;
    }
    // http://127.0.0.1:8811/?s=/index/index/singwa
    public function singwa() {
        echo time();
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 'hessdggsg' . $name.time();
    }

}
