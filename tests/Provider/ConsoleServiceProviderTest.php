<?php

/*
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli\Tests\Provider;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Pimple\Container;
use Lemon\Cli\Console\ContainerAwareApplication;
use Lemon\Cli\Provider\ConsoleServiceProvider;

/**
 * Test class ConsoleServiceProvider
 */
class ConsoleServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test register method
     */
    public function testRegister()
    {
        $container = new Container();
        $container['console.name'] = 'Test app';
        $container['console.version'] = '1.0.0';
        $container['eventDispatcher'] = function () {
            return new EventDispatcher();
        };

        $this->assertFalse(isset($container['console']));

        $provider = new ConsoleServiceProvider();
        $provider->register($container);

        $this->assertTrue(isset($container['console']));
        $this->assertInstanceOf(ContainerAwareApplication::class, $container['console']);
    }
}
