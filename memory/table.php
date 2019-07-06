<?php

//创建内存表
//1024行
$table = new swoole_table(1024);

//内存表增加 一列
$table->column('id',$table::TYPE_INT,4);
$table->column('name',$table::TYPE_STRING,64);
$table->column('age',$table::TYPE_INT,3);
$table->create();//创建

$table->set('singwa_imooc',['id'=>1,'name'=>'singwa','age'=>30]);
//另一种方案
$table['singwa_imooc_2'] = [
	'id'=>2,
	'name'=>'ssss',
	'age'=>31
];
$table->incr('singwa_imooc_2','age',2);

print_r($table->get('singwa_imooc'));
print_r($table->get('singwa_imooc_2'));
$table->del('singwa_imooc_2');//删除
echo "删除后".PHP_EOL;
print_r($table->get('singwa_imooc_2'));
