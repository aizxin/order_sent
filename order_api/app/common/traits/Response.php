<?php
/**
 * FileName: Response.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: 永 | <chuanshuo_yongyuan@163.com>
 * @date  : 2019/10/31 14:43
 */

declare (strict_types = 1);

namespace app\common\traits;

use think\exception\HttpResponseException;

/**
 * Trait Response
 * @package app\common\traits
 */
trait Response
{
    /**
     * @var int
     */
    protected $code = 200;
    /**
     * @var string
     */
    protected $message = 'OK';
    /**
     * @var array
     */
    protected $data = [];

    /**
     * 获取响应码
     * @return int
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * 设置响应码
     *
     * @param int $code
     *
     * @return object
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function setCode(int $code): object
    {
        $this->code = $code;

        return $this;
    }

    /**
     * 获取需要相应的消息提示
     * @return string
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * 设置需要响应的消息提示
     *
     * @param string $message
     *
     * @return object
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function setMessage(string $message): object
    {
        $this->message = $message;

        return $this;
    }

    /**
     * 获取响应数据
     * @return array
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 设置需要响应的数据
     *
     * @param array $data
     *
     * @return object
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    public function setData($data): object
    {
        $this->data = $data;

        return $this;
    }

    /**
     * 判断需要响应的数据是否为空
     * @return bool
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    protected function isEmpty(): bool
    {
        return empty($this->getData());
    }

    /**
     * 响应失败的提示
     *
     * @param string $msg
     * @param int    $code
     *
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    protected function error(string $msg = '服务器忙,请刷新后再试~', int $code = 0): void
    {
        $this->setMessage($msg)->setCode($code)->returnResponse();
    }

    /**
     * 响应成功的提示
     *
     * @param string $msg
     * @param int    $code
     *
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    protected function success(string $msg = '操作成功~', int $code = 200): void
    {
        $this->setMessage($msg)->setCode($code)->returnResponse();
    }

    /**
     * 返回空结果
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    protected function emptyData(): void
    {
        $this->setCode(200)->setMessage('数据为空')->returnResponse();
    }

    /**
     * 结果响应
     *
     * @param array $data 需要响应的数据
     *
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    protected function response($data = []): void
    {
        $this->setData($data);

        if ($this->isEmpty()) {
            $this->emptyData();
        }

        $this->returnResponse();
    }

    /**
     * 抛出最终 Response 结果
     * @author: 永 | <chuanshuo_yongyuan@163.com>
     */
    protected function returnResponse()
    {
        $returnData = [
            'code'    => $this->getCode(),
            'message' => $this->getMessage(),
            'data'    => $this->getData(),
        ];
        $return = json($returnData);

        throw new HttpResponseException($return);
    }

}