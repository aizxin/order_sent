<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @date  : 2019/11/11 15:55
 */
declare (strict_types = 1);

namespace app\common\command\create;


use app\common\command\Create;
use think\console\input\Option;

class Controller extends Create
{

    protected $type = "Controller";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:controller')
            ->addOption('api', null, Option::VALUE_NONE, 'Generate an api controller class.')
            ->addOption('plain', null, Option::VALUE_NONE, 'Generate an empty controller class.')
            ->setDescription('Create a new resource controller class');
    }

    protected function getStub(): string
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;

        return $stubPath . 'controller.stub';
    }

    protected function getClassName(string $name): string
    {
        return parent::getClassName($name) . ($this->app->config->get('route.controller_suffix') ? 'Controller' : '');
    }

    protected function getNamespace(string $app): string
    {
        return parent::getNamespace($app) . '\\controller';
    }

}
