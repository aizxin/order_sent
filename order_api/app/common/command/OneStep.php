<?php
/**
 * FileName: OneStep.php
 * ==============================================
 * Copy right 2016-2018
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @date  : 2019/11/11 18:04
 */
declare (strict_types = 1);

namespace app\common\command;

use think\console\Input;
use think\console\Output;

class OneStep extends Create
{
    protected $type = "Controller";

    protected function configure()
    {
        parent::configure();
        $this->setName('test')
            ->setDescription('Create a new resource controller class');
    }

    public function getStub()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'create' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;

        return [
            'controller'  => $stubPath . 'controller.stub',
            'logic'       => $stubPath . 'logic.stub',
            'transformer' => $stubPath . 'transformer.stub',
            'model'       => $stubPath . 'model.stub',
            'validate'    => $stubPath . 'validate.stub',
        ];
    }

    protected function execute(Input $input, Output $output)
    {
        $stubPaths = $this->getStub();
        foreach ($stubPaths as $key => $val) {
            $name = trim($input->getArgument('name'));
            $classname = $this->getClassNames($name, $key);
            $pathname = $this->getPathName($classname);

            if (is_file($pathname)) {
                $output->writeln('<error>' . $this->type . ':' . $classname . ' already exists!</error>');

                return false;
            }

            if ( ! is_dir(dirname($pathname))) {
                mkdir(dirname($pathname), 0755, true);
            }

            file_put_contents($pathname, $this->buildClasss($classname, $val));

            $output->writeln('<info>' . $this->type . ':' . $classname . ' created successfully.</info>');
        }
    }

    protected function buildClasss(string $name, string $stubPath)
    {
        $stub = file_get_contents($stubPath);

        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);

        return str_replace(['{%time%}', '{%lowercaseClassName%}', '{%className%}', '{%actionSuffix%}', '{%namespace%}', '{%app_namespace%}'], [
            date('Y-m-d H:i', time()),
            lcfirst($class),
            $class,
            $this->app->config->get('route.action_suffix'),
            $namespace,
            $this->app->getNamespace(),
        ], $stub);
    }

    protected function getClassNames(string $name, string $type): string
    {
        if (strpos($name, '\\') !== false) {
            return $name;
        }

        if (in_array($type, ['model', 'validate', 'logic', 'transformer'])) {
            $app = 'common';
        } else {
            $app = 'admin';
        }


        if (strpos($name, '/') !== false) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->getNamespaces($app, $type) . '\\' . $name;
    }

    protected function getNamespaces(string $app, string $type): string
    {
        return parent::getNamespace($app) . '\\' . $type;
    }

}