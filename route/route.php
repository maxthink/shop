<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,maxthink';
});

Route::rule('wx/device/work/:id', 'wx/device/work')->pattern(['id' => '\d+'])->name('wx_dev_work');
Route::rule('wx/device1/work/:id', 'wx/device1/work')->pattern(['id' => '\d+'])->name('wx_dev_work1');

Route::rule('wx/device/record/:id', 'wx/device/record', 'get')->pattern(['id' => '\d+'])->name('wx_dev_record');
Route::rule('wx/device1/record/:id', 'wx/device1/record', 'get')->pattern(['id' => '\d+'])->name('wx_dev_record1');

Route::rule('wx/device/bill', 'wx/device/bill','post')->pattern(['did' => '\d+','mealid' => '\d+'])->name('wx_dev_bill');


Route::rule('api/device/100/work', 'api/api100/work','post');   //给上海用的设备启动接口


return [

];
