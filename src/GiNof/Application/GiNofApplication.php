<?php

namespace GiNof\Application;

use Symfony\Component\Console\Application;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

use GiNof\Command\UpdateNotificationCommand;
use GiNof\Command\ShowNotificationCommand;
use GiNof\Command\ReadNotificationCommand;

class GiNofApplication extends Application
{
    public function __construct()
    {
        parent::__construct('GiNof', '1.0.0-dev');

        $config = $this->getConfiguration();

        $this->addCommands([
            new UpdateNotificationCommand($config),
            new ShowNotificationCommand($config),
            new ReadNotificationCommand($config),
        ]);
    }

    private function getConfiguration()
    {
        $locator  = new FileLocator([__DIR__.'/../../../']);
        $resource = $locator->locate('ginof.yml');
        $config   = Yaml:: parse($resource);

        return $config;
    }
}
