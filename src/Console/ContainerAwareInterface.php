<?php
/**
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli\Console;

/**
 * Inteface ContainerAwareInterface
 */
interface ContainerAwareInterface
{
    /**
     * Get the Container.
     *
     * @return \Pimple\Container
     */
    public function getContainer();
}
