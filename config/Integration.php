<?php
namespace Aura\Project_Kernel\Aura\Cli_Kernel\Config;

use Aura\Di\Container;
use Aura\Project_Kernel\Config;

class Integration extends Config
{
    public function define(Container $di)
    {
        $di->params['Aura\Cli\Stdio'] = array(
            'stdin' => $di->lazyNew('Aura\Cli\Stdio\Handle', array(
                'name' => 'php://memory',
                'mode' => 'r+',
            )),
            'stdout' => $di->lazyNew('Aura\Cli\Stdio\Handle', array(
                'name' => 'php://memory',
                'mode' => 'w+',
            )),
            'stderr' => $di->lazyNew('Aura\Cli\Stdio\Handle', array(
                'name' => 'php://memory',
                'mode' => 'w+',
            )),
            'formatter' => $di->lazyNew('Aura\Cli\Stdio\Formatter'),
        );
    }

    public function modify(Container $di)
    {
        $dispatcher = $di->get('cli_dispatcher');
        $stdio = $di->get('cli_stdio');

        $dispatcher->setObject(
            'aura-integration-hello',
            function () use ($stdio) {
                $stdio->outln("Hello World!");
            }
        );

        $dispatcher->setObject(
            'aura-integration-exception',
            function () {
                throw new \Exception('mock exception');
            }
        );
    }
}
