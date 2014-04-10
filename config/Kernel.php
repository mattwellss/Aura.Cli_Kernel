<?php
namespace Aura\Cli_Kernel\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Kernel extends Config
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

        $help_service = $di->get('cli_help_service');
        $help_service->set('aura-integration-hello', function () use ($di) {
            $help = $di->newInstance('Aura\Cli\Help');
            $help->setSummary('Integration test command for hello world.');
            $help->setDescr('The quick brown fox jumps over the lazy dog.');
            return $help;
        });

        $help_service->set('aura-integration-exception', function () use ($di) {
            $help = $di->newInstance('Aura\Cli\Help');
            $help->setSummary('Throws an exception.');
            return $help;
        });
    }
}
