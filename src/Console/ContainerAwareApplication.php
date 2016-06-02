<?php

/*
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli\Console;

use Pimple\Container;
use Symfony\Component\Console\Application;

/**
 * Class ContainerAwareApplication
 *
 * Console application be able setted and getted container
 */
class ContainerAwareApplication extends Application implements ContainerAwareInterface
{
    /**
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * Sets a pimple instance onto this application.
     *
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get the Container.
     *
     * @return \Pimple\Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
