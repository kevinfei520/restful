<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::resource(':version/user','api/:version.User');   //注册一个资源路由，对应restful各个方法
Route::resource(':version/classroom','api/:version.Classroom');   //注册一个资源路由，对应restful各个方法
Route::resource(':version/course','api/:version.Course');   //注册一个资源路由，对应restful各个方法
Route::rule(':version/token/token','api/:version.Token/token');
Route::miss('Error/index');

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
];
