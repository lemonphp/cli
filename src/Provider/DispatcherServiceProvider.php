<?php
/**
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
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * DispatcherServiceProvider
 *
 * Registers EventDispatcher and related services with the Pimple Container
 */
class DispatcherServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers event dispatcher services on the given container.
     *
     * @param \Pimple\Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['dispatcher'] = function () {
            return new EventDispatcher();
        };
    }
}
