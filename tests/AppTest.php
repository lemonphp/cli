<?php

/*
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli\Tests;

/**
 * Application test cases.
 */
class AppTest extends \PHPUnit_Framework_TestCase
{
    const NAME    = 'Test App';
    const VERSION = '1.0.1';

    /**
     * @var \Lemon\Cli\App
     */
    protected $app;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->app = new \Lemon\Cli\App(self::NAME, self::VERSION);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->app = null;
        parent::tearDown();
    }

    /**
     * Test constructor
     */
    public function testConstructor()
    {
        $ref = new \ReflectionClass(get_class($this->app));
        $booted = $ref->getProperty('booted');
        $booted->setAccessible(true);
        $subscribers = $ref->getProperty('eventSubscribers');
        $subscribers->setAccessible(true);
        $container = $ref->getProperty('container');
        $container->setAccessible(true);

        $con = $container->getValue($this->app);

        $this->assertFalse($booted->getValue($this->app));
        $this->assertCount(0, $subscribers->getValue($this->app));
        $this->assertInstanceOf(\Pimple\Container::class, $con);

        $this->assertArrayHasKey('console', $con);
        $this->assertArrayHasKey('console.name', $con);
        $this->assertArrayHasKey('console.version', $con);
        $this->assertArrayHasKey('event-dispatcher', $con);

        $this->assertInstanceOf(\Symfony\Component\Console\Application::class, $con['console']);
        $this->assertInstanceOf(\Symfony\Component\EventDispatcher\EventDispatcher::class, $con['event-dispatcher']);

        $this->assertSame(self::NAME, $con['console']->getName());
        $this->assertSame(self::VERSION, $con['console']->getVersion());
    }

    /**
     * Test constructor with values
     */
    public function testContructorWithValues()
    {
        $app = new \Lemon\Cli\App(self::NAME, self::VERSION, [
            'foo' => 'bar',
            'baz' => function () {
                return new \DateTime();
            },
        ]);

        $container = new \ReflectionProperty(get_class($app), 'container');
        $container->setAccessible(true);

        $con = $container->getValue($app);

        $this->assertArrayHasKey('foo', $con);
        $this->assertArrayHasKey('baz', $con);

        $this->assertArrayNotHasKey('abc', $con);

        $this->assertSame('bar', $con['foo']);
        $this->assertInstanceOf(\DateTime::class, $con['baz']);
    }

    /**
     * Test register without values
     */
    public function testRegister()
    {
        $provider = $this->getMock(\Pimple\ServiceProviderInterface::class);
        $provider->expects($this->once())->method('register');

        $this->assertSame($this->app, $this->app->register($provider));
    }

    /**
     * Test register event subscriber without values
     */
    public function testRegisterWithEventSubscriber()
    {
        $provider = new \Lemon\Cli\Tests\Stub\FooProvider();

        $this->app->register($provider);
        $ref = new \ReflectionProperty(get_class($this->app), 'eventSubscribers');
        $ref->setAccessible(true);

        $subscribers = $ref->getValue($this->app);

        $this->assertCount(1, $subscribers);
        $this->assertContains($provider, $subscribers);
    }

    /**
     * Test register with values
     */
    public function testRegisterWithValues()
    {
        $provider = $this->getMock(\Pimple\ServiceProviderInterface::class);

        $this->app->register($provider, [
            'foo' => 'bar',
            'baz' => function () {
                return new \DateTime();
            },
        ]);

        $ref = new \ReflectionProperty(get_class($this->app), 'container');
        $ref->setAccessible(true);

        $container = $ref->getValue($this->app);

        $this->assertArrayHasKey('foo', $container);
        $this->assertArrayHasKey('baz', $container);
        $this->assertSame('bar', $container['foo']);
        $this->assertInstanceOf(\DateTime::class, $container['baz']);
    }

    /**
     * Test boot method
     */
    public function testBoot()
    {
        $ref = new \ReflectionClass($this->app);
        $booted = $ref->getProperty('booted');
        $boot   = $ref->getMethod('boot');

        $booted->setAccessible(true);
        $boot->setAccessible(true);

        $fooProvider = new \Lemon\Cli\Tests\Stub\FooProvider();
        $this->app->register($fooProvider);

        $this->assertFalse($booted->getValue($this->app));
        $this->assertSame(0, \Lemon\Cli\Tests\Stub\FooProvider::$called);

        $boot->invoke($this->app);

        $this->assertTrue($booted->getValue($this->app));
        $this->assertSame(1, \Lemon\Cli\Tests\Stub\FooProvider::$called);

        $boot->invoke($this->app);
        $this->assertSame(1, \Lemon\Cli\Tests\Stub\FooProvider::$called);
    }

    /**
     * Test run method with custom input and output
     */
    public function testRunWithCustomInputOutput()
    {
        $input  = $this->getMock(\Symfony\Component\Console\Input\InputInterface::class);
        $output = $this->getMock(\Symfony\Component\Console\Output\OutputInterface::class);

        $ref = new \ReflectionProperty(get_class($this->app), 'container');
        $ref->setAccessible(true);
        $container = $ref->getValue($this->app);

        $container['console'] = $this->getMock(\Symfony\Component\Console\Application::class);
        $container['console']->expects($this->once())->method('run')->with($input, $output);

        $ref->setValue($this->app, $container);

        $this->app->run($input, $output);
    }

    /**
     * Test add command
     */
    public function testAddCommand()
    {
        $this->app->addCommand(new \Lemon\Cli\Console\Command\GreetCommand());

        $ref = new \ReflectionProperty(get_class($this->app), 'container');
        $ref->setAccessible(true);
        $container = $ref->getValue($this->app);

        $this->assertTrue($container['console']->has('demo:greet'));
        $this->assertFalse($container['console']->has('demo:echo'));
    }

    /**
     * Test allow add an instance of \Symfony\Component\Console\Command\Command
     */
    public function testAllowAddSymfonyCommand()
    {
        $this->app->addCommand(new \Symfony\Component\Console\Command\Command('demo:greet'));

        $ref = new \ReflectionProperty(get_class($this->app), 'container');
        $ref->setAccessible(true);
        $container = $ref->getValue($this->app);

        $this->assertTrue($container['console']->has('demo:greet'));
    }
}
