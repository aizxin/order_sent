<?php
/**
 * FileName: demo.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @date  : 2019/10/29 14:52
 */
declare (strict_types = 1);

use think\facade\Route;
use app\common\middleware\admin\Auth;

// 上传 资源
Route::group('/upload', function () {
    Route::any('img', 'img')->prefix('Upload/')->name('img');
    Route::any('file', 'file')->prefix('Upload/')->name('file');
});

// 登录
Route::group('/auth', function () {
    Route::post('login', 'login')->name('auth');
    Route::get('info', 'info')->name('auth')->middleware(Auth::class);
    Route::get('menu', 'menu')->name('auth')->middleware(Auth::class);
})->prefix('Auth/');

Route::group(function () {
    // 管理员用户管理
    Route::group('/admin', function () {
        Route::get('lists', 'lists')->name('admin');
        Route::get('info', 'info')->name('admin');
        Route::post('add', 'add')->name('admin');
        Route::post('edit', 'edit')->name('admin');
        Route::post('delete', 'delete')->name('admin');
        Route::post('status', 'status')->name('admin');
        Route::post('changePassword', 'changePassword')->name('admin');
        Route::post('role', 'role')->name('admin');
        Route::post('add_role', 'addRole')->name('admin');
    })->prefix('Admin/');

    // 菜单管理
    Route::group('/rule', function () {
        Route::get('lists', 'lists')->name('rule');
        Route::get('info', 'info')->name('rule');
        Route::post('add', 'add')->name('rule');
        Route::post('edit', 'edit')->name('rule');
        Route::post('delete', 'delete')->name('rule');
        Route::post('status', 'status')->name('rule');
    })->prefix('Rule/');

    // 角色管理
    Route::group('/role', function () {
        Route::get('lists', 'lists')->name('role');
        Route::get('info', 'info')->name('role');
        Route::post('add', 'add')->name('role');
        Route::post('edit', 'edit')->name('role');
        Route::post('delete', 'delete')->name('role');
        Route::post('status', 'status')->name('role');
        Route::post('rule', 'rule')->name('role');
    })->prefix('Role/');

    // 登录日志管理
    Route::group('/admin_log', function () {
        Route::get('lists', 'lists')->name('admin_log');
        Route::post('delete', 'delete')->name('admin_log');
    })->prefix('AdminLog/');

    // 行为日志
    Route::group('/action_log', function () {
        Route::get('lists', 'lists')->name('log');
        Route::post('delete', 'delete')->name('log');
    })->prefix('ActionLog/');

    // 文章
    Route::group('/article', function () {
        Route::get('lists', 'lists')->name('article');
        Route::get('info', 'info')->name('article');
        Route::post('add', 'add')->name('article');
        Route::post('edit', 'edit')->name('article');
        Route::post('delete', 'delete')->name('article');
        Route::post('status', 'status')->name('article');
    })->prefix('Article/');

    // 医院分类管理
    Route::group('/cate', function () {
        Route::get('lists', 'lists')->name('cate');
        Route::get('info', 'info')->name('cate');
        Route::post('add', 'add')->name('cate');
        Route::post('edit', 'edit')->name('cate');
        Route::post('delete', 'delete')->name('cate');
        Route::post('status', 'status')->name('cate');
    })->prefix('Cate/');

    // 医院管理
    Route::group('/hospital', function () {
        Route::get('lists', 'lists')->name('hospital');
        Route::get('info', 'info')->name('hospital');
        Route::post('add', 'add')->name('hospital');
        Route::post('edit', 'edit')->name('hospital');
        Route::post('delete', 'delete')->name('hospital');
        Route::post('status', 'status')->name('hospital');
    })->prefix('Hospital/');

    // 医院管理员
    Route::group('/hospital_user', function () {
        Route::get('lists', 'lists')->name('hospital_user');
        Route::get('info', 'info')->name('hospital_user');
        Route::post('add', 'add')->name('hospital_user');
        Route::post('edit', 'edit')->name('hospital_user');
        Route::post('delete', 'delete')->name('hospital_user');
        Route::post('status', 'status')->name('hospital_user');
    })->prefix('HospitalUser/');

    // 配单 管理
    Route::group('/pairing_order', function () {
        Route::get('lists', 'lists')->name('pairing_order');
        Route::get('info', 'info')->name('pairing_order');
        Route::post('add', 'add')->name('pairing_order');
        Route::post('edit', 'edit')->name('pairing_order');
        Route::post('delete', 'delete')->name('pairing_order');
        Route::post('status', 'status')->name('pairing_order');
        Route::post('pairing', 'pairing')->name('pairing_order');
    })->prefix('PairingOrder/');

    // 会员用户管理
    Route::group('/user', function () {
        Route::get('lists', 'lists')->name('user');
        Route::get('info', 'info')->name('user');
        Route::post('add', 'add')->name('user');
        Route::post('edit', 'edit')->name('user');
        Route::post('delete', 'delete')->name('user');
        Route::post('status', 'status')->name('user');
        Route::post('pairing', 'pairing')->name('user');
    })->prefix('User/');
})->middleware(Auth::class);

Route::miss(function () {
    return json([
        'code' => 404,
        'msg'  => '页面不存在',
    ], 404);
});
