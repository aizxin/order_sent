<?php
/**
 * FileName: Logic.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @date  : 2019/11/11 15:43
 */
declare (strict_types = 1);
namespace app\common\command\create;


use app\common\command\Create;

class Transformer extends Create
{
    protected $type = "Transformer";

    protected function configure()
    {
        parent::configure();
        $this->setName('create:transformer')
            ->setDescription('Create a transformer class');
    }

    protected function getStub()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;

        return $stubPath . 'transformer.stub';
    }

    protected function getNamespace(string $app): string
    {
        return parent::getNamespace($app) . '\\transformer';
    }
}