<?php

/*
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli\Tests\Provider;

use Pimple\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Lemon\Cli\Provider\EventDispatcherServiceProvider;

/**
 * Test class EventDispatcherServiceProvider
 */
class EventDispatcherServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test register method
     */
    public function testRegister()
    {
        $container = new Container();

        $this->assertFalse(isset($container['event-dispatcher']));

        $provider = new EventDispatcherServiceProvider();
        $provider->register($container);

        $this->assertTrue(isset($container['event-dispatcher']));
        $this->assertInstanceOf(EventDispatcher::class, $container['event-dispatcher']);
    }
}
