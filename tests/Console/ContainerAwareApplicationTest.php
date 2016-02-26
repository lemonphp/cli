<?php
/**
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli\Tests\Console;

use Pimple\Container;
use Lemon\Cli\Console\ContainerAwareApplication;

/**
 * Test class ContrainerAwareApplication
 */
class ContainerAwareApplicationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test setContainer() method
     */
    public function testSetContainer()
    {
        $container = new Container();
        $app = new ContainerAwareApplication();
        $ref = new \ReflectionProperty(get_class($app), 'pimple');
        $ref->setAccessible(true);
        // Check before set
        $this->assertNull($ref->getValue($app));
        // Check after set
        $app->setContainer($container);
        $this->assertSame($container, $ref->getValue($app));
    }

    /**
     * Test getContainer method
     */
    public function testGetContainer()
    {
        $container = new Container();
        $app = new ContainerAwareApplication();
        $ref = new \ReflectionProperty(get_class($app), 'pimple');
        $ref->setAccessible(true);
        $ref->setValue($app, $container);

        $this->assertSame($container, $app->getContainer());
    }
}
