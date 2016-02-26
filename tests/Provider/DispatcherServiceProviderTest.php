<?php
/**
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli\Tests\Provider;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Lemon\Cli\Provider\DispatcherServiceProvider;
use Pimple\Container;

/**
 * Test class DispatcherServiceProvider
 */
class DispatcherServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test register method
     */
    public function testRegister()
    {
        $container = new Container();

        $this->assertFalse(isset($container['dispatcher']));

        $provider = new DispatcherServiceProvider();
        $provider->register($container);

        $this->assertTrue(isset($container['dispatcher']));
        $this->assertInstanceOf(EventDispatcher::class, $container['dispatcher']);
    }
}
