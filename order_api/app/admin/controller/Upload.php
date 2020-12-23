<?php
/**
 * FileName: Upload.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/6/28 3:08 下午
 */
declare (strict_types = 1);

namespace app\admin\controller;


use app\common\controller\BaseController;

class Upload extends BaseController
{
    use \app\common\traits\Upload;

    public function img()
    {
        try {
            $result = $this->uploadImg();
            if (is_array($result)) {
                foreach ($result as $key => $value) {
                    $result[ $key ]->url = $this->request->domain() . $result[ $key ]->url;
                }
            } else {
                $result->url = $this->request->domain() . $result->url;
            }

            return json([
                'data'    => $result,
                'code'    => 200,
                'message' => '上传成功',
            ]);
        } catch (\Exception $exception) {
            trace($exception->getMessage(), 'info');
            $this->error('请选择正确文件上传!');
        }
    }

    public function file()
    {
        try {
            $result = $this->uploadFile();
            if (is_array($result)) {
                foreach ($result as $key => $value) {
                    $result[ $key ]->url = $this->request->domain() . $result[ $key ]->url;
                }
            } else {
                $result->url = $this->request->domain() . $result->url;
            }

            return json([
                'data'    => $result,
                'code'    => 200,
                'message' => '上传成功',
            ]);
        } catch (\Exception $exception) {
            trace($exception->getMessage(), 'info');
            $this->error('请选择正确文件上传!');
        }
    }
}