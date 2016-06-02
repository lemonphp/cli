<?php

/*
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Lemon\Cli\Console\ContainerAwareApplication;

/**
 * Class ConsoleServiceprovider
 *
 * Registers ContainerAwareApplication and related services with the Pimple Container
 */
class ConsoleServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers console services on the given container.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['console'] = function ($pimple) {
            $console = new ContainerAwareApplication($pimple['console.name'], $pimple['console.version']);
            $console->setDispatcher($pimple['event-dispatcher']);
            $console->setContainer($pimple);

            return $console;
        };
    }
}
