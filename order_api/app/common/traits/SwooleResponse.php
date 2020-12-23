<?php
/**
 * FileName: SwooleResponse.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2020/4/26 10:56 上午
 */
declare (strict_types = 1);

namespace app\common\traits;


trait SwooleResponse
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
     * @var string
     */
    protected $event;

    /**
     * @return int
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 设置响应码
     *
     * @param int $code
     *
     * @return object
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    public function setCode(int $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * 获取需要相应的消息提示
     * @return string
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 设置需要响应的消息提示
     *
     * @param string $message
     *
     * @return object
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * 获取event
     * @return string
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * 设置event
     *
     * @param string $message
     *
     * @return object
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    public function setEvent(string $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * 获取响应数据
     * @return array
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
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
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    public function setData($data = [])
    {
        $this->data = $data;

        return $this;
    }

    /**
     * 判断需要响应的数据是否为空
     * @return bool
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    protected function isEmpty()
    {
        return empty($this->getData());
    }

    /**
     * 响应失败的提示
     *
     * @param string $msg
     * @param int    $code
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    protected function error(string $msg = '服务器忙,请刷新后再试~', int $code = 0)
    {
        return $this->setMessage($msg)->setCode($code)->setData()->returnResponse();
    }

    /**
     * 响应成功的提示
     *
     * @param string $msg
     * @param int    $code
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    protected function success($data = [], string $msg = '操作成功~', int $code = 200)
    {
        return $this->setMessage($msg)->setCode($code)->setData($data)->returnResponse();
    }

    /**
     * 结果响应
     *
     * @param array $data 需要响应的数据
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2020/4/26 11:15 上午
     */
    protected function response($data = [])
    {
        $this->setCode(200)->setMessage('ok')->setData($data);

        return $this->returnResponse();
    }

    /**
     * 抛出最终 Response 结果
     *
     */
    protected function returnResponse()
    {
        $returnData = [
            'code'    => $this->getCode(),
            'message' => $this->getMessage(),
            'event'   => $this->getEvent(),
        ];

        if ($this->getData()) {
            $returnData['data'] = $this->getData();
        }

        return json_encode($returnData);
    }
}