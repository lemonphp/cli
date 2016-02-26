<?php

namespace Lemon\Cli\Tests\Stub;

use Pimple\ServiceProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Stub class provider implement `Pimple\ServiceProviderInterface`
 * and `Symfony\Component\EventDispatcher\EventSubscriberInterface`
 */
class FooProvider implements ServiceProviderInterface, EventSubscriberInterface
{
    /**
     * @var int
     */
    public static $called = 0;

    /**
     * {@inheritdoc}
     */
    public function register(\Pimple\Container $pimple)
    {
        // no do thing
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        self::$called++;

        return [];
    }
}
