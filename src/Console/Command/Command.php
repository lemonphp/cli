<?php

/*
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli\Console\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Lemon\Cli\Console\ContainerAwareInterface;

/**
 * Abstract class for commands
 */
abstract class Command extends BaseCommand
{

    /**
     * Get application container
     *
     * @return \Pimple\Container|null
     */
    public function getContainer()
    {
        $app = $this->getApplication();

        return ($app instanceof ContainerAwareInterface) ? $app->getContainer() : null;
    }

    /**
     * Get service in container
     *
     * Returns a service contained in the application container or null if none
     * is found with that name.
     *
     * @param string $name Name of service
     * @return mixed
     */
    public function getService($name)
    {
        $container = $this->getContainer();

        return !is_null($container) && isset($container[$name]) ? $container[$name] : null;
    }
}
