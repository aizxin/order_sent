<?php

namespace app\common\validate;

use think\Validate;

class Admin extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id'               => 'require',
        'username'         => 'require|max:50|chsAlphaNum|unique:admin_user',
        'name'             => 'require|max:50|chsAlphaNum',
        'old_password'     => 'require|max:50|checkOld',
        'password'         => 'require|max:50',
        'confirm_password' => 'require|max:50|confirm:password',
        'email'            => 'email',
        'mobile'           => 'mobile',
        'status'           => 'require|in:0,1',
        'role'             => 'require|array',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require'               => '请选择用户',
        'username.require'         => '用户名必须填写',
        'username.max'             => '用户名最多不能超过50个字符',
        'username.chsAlphaNum'     => '用户名不能含有特殊字符',
        'username.unique'          => '该用户已经存在',
        'name.require'             => '用户名称必须填写',
        'name.max'                 => '用户名称最多不能超过50个字符',
        'name.chsAlphaNum'         => '用户名称不能含有特殊字符',
        'old_password.require'     => '原密码必须填写',
        'old_password.max'         => '原密码最多不能超过50个字符',
        'password.require'         => '密码必须填写',
        'password.max'             => '密码最多不能超过50个字符',
        'confirm_password.require' => '确认密码必须填写',
        'confirm_password.max'     => '确认密码最多不能超过50个字符',
        'confirm_password.confirm' => '确认密码和密码不一致',
        'email.email'              => '邮箱格式不正确',
        'mobile.mobile'            => '电话号码不正确',
        'status.require'           => '请选择状态',
        'status.in'                => '状态不正确',
        'role.require'             => '请选择角色',
        'role.array'               => '角色格式错误',
    ];

    protected $scene = [
        'add'            => ['username', 'name', 'password', 'confirm_password', 'email', 'mobile', 'status'],
        'edit'           => ['id', 'name', 'email', 'mobile', 'status'],
        'changePassword' => ['old_password', 'password', 'confirm_password'],
        'role'           => ['id', 'role'],
    ];

    public function checkOld($value, $rule, $data)
    {
        $result = true;
        $old_password = $data['old_password'];
        $confirm_password = $data['confirm_password'];
        if ($old_password == $confirm_password) {
            return '新密码与旧密码一致';
        }

        return $result;
    }
}

