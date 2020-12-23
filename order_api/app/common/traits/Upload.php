<?php
/**
 * FileName: Upload.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: 永 | <chuanshuo_yongyuan@163.com>
 * @date  : 2019/11/26 10:38
 */
declare (strict_types = 1);

namespace app\common\traits;

use app\common\model\File as FileModel;
use app\common\model\Picture;
use think\facade\Filesystem;

trait Upload
{
    public function uploadImg()
    {
        $files = request()->file('file');
        $result = [];
        if (is_array($files)) {
            foreach ($files as $key => $file) {
                $result[] = $this->image($file);
            }
        } else {
            $result = $this->image($files);
        }

        return $result;
    }

    public function uploadFile()
    {
        $files = request()->file('file');
        $result = [];
        if (is_array($files)) {
            foreach ($files as $key => $file) {
                $result[] = $this->_uploadFile($file);
            }
        } else {
            $result = $this->_uploadFile($files);
        }

        return $result;
    }

    public function _uploadFile($file, $dir = 'file', $disk = 'public')
    {
        if (empty($file)) {
            return false;
        }
        $result = FileModel::infoByHash($file->hash());
        if (empty($result)) {
            $fileSystem = Filesystem::disk($disk);
            $config = config('filesystem.disks.' . $disk);
            $path = $fileSystem->putFile($dir, $file);
            $name = msubstr($file->getOriginalName(), 0, 200, "utf-8", false);
            $data = [
                'original_name' => $name,
                'file_name'     => $name,
                'sha1'          => $file->hash(),
                'md5'           => $file->md5(),
                'path'          => $config['url'] . DIRECTORY_SEPARATOR . $path,
                'url'           => $config['url'] . DIRECTORY_SEPARATOR . $path,
            ];
            $result = FileModel::create($data);
        }

        return $result;
    }

    public function image($file, $dir = 'images', $disk = 'public')
    {
        if (empty($file)) {
            return false;
        }
        $result = Picture::infoByHash($file->hash());
        if (empty($result)) {
            $fileSystem = Filesystem::disk($disk);
            $config = config('filesystem.disks.' . $disk);
            $path = $fileSystem->putFile($dir, $file);
            $name = msubstr($file->getOriginalName(), 0, 200, "utf-8", false);
            $data = [
                'original_name' => $name,
                'file_name'     => $name,
                'sha1'          => $file->hash(),
                'md5'           => $file->md5(),
                'path'          => $config['url'] . DIRECTORY_SEPARATOR . $path,
                'url'           => $config['url'] . DIRECTORY_SEPARATOR . $path,
            ];
            $result = Picture::create($data);
        }

        return $result;
    }
}