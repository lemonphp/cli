<?php
/**
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
        $this->assertInstanceOf(\Pimple\Container::class, $this->app);

        $ref = new \ReflectionClass(get_class($this->app));
        $booted = $ref->getProperty('booted');
        $booted->setAccessible(true);
        $providers = $ref->getProperty('providers');
        $providers->setAccessible(true);

        $this->assertFalse($booted->getValue($this->app));
        $this->assertCount(2, $providers->getValue($this->app));

        $this->assertArrayHasKey('console', $this->app);
        $this->assertArrayHasKey('console.name', $this->app);
        $this->assertArrayHasKey('console.version', $this->app);
        $this->assertArrayHasKey('dispatcher', $this->app);

        $this->assertInstanceOf(\Symfony\Component\Console\Application::class, $this->app['console']);
        $this->assertInstanceOf(\Symfony\Component\EventDispatcher\EventDispatcher::class, $this->app['dispatcher']);

        $this->assertSame(self::NAME, $this->app['console']->getName());
        $this->assertSame(self::VERSION, $this->app['console']->getVersion());
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

        $this->assertArrayHasKey('foo', $app);
        $this->assertArrayHasKey('baz', $app);

        $this->assertArrayNotHasKey('abc', $app);

        $this->assertSame('bar', $app['foo']);
        $this->assertInstanceOf(\DateTime::class, $app['baz']);
    }

    /**
     * Test register without values
     */
    public function testRegister()
    {
        $provider = $this->getMock(\Pimple\ServiceProviderInterface::class);
        $provider->expects($this->once())->method('register');

        $this->assertSame($this->app, $this->app->register($provider));

        $ref = new \ReflectionProperty(get_class($this->app), 'providers');
        $ref->setAccessible(true);
        $providers = $ref->getValue($this->app);

        $this->assertContains($provider, $providers);
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

        $this->assertArrayHasKey('foo', $this->app);
        $this->assertArrayHasKey('baz', $this->app);
        $this->assertSame('bar', $this->app['foo']);
        $this->assertInstanceOf(\DateTime::class, $this->app['baz']);
    }

    /**
     * Test boot method
     */
    public function testBoot()
    {
        $ref = new \ReflectionProperty(get_class($this->app), 'booted');
        $ref->setAccessible(true);
        $fooProvider = new \Lemon\Cli\Tests\Stub\FooProvider();
        $this->app->register($fooProvider);

        $this->assertEquals(0, \Lemon\Cli\Tests\Stub\FooProvider::$called);

        $this->app->boot();

        $this->assertTrue($ref->getValue($this->app));
        $this->assertEquals(1, \Lemon\Cli\Tests\Stub\FooProvider::$called);

        $this->app->boot();
        $this->assertEquals(1, \Lemon\Cli\Tests\Stub\FooProvider::$called);
    }

    /**
     * Test run method with custom input and output
     */
    public function testRunWithCustomInputOutput()
    {
        $input  = $this->getMock(\Symfony\Component\Console\Input\InputInterface::class);
        $output = $this->getMock(\Symfony\Component\Console\Output\OutputInterface::class);

        $this->app['console'] = $this->getMock(\Symfony\Component\Console\Application::class);
        $this->app['console']->expects($this->once())->method('run')->with($input, $output);

        $this->app->run($input, $output);
    }

    /**
     * Test add command
     */
    public function testAddCommand()
    {
        $this->app->addCommand(new \Lemon\Cli\Console\Command\GreetCommand());

        $this->assertTrue($this->app['console']->has('demo:greet'));
        $this->assertFalse($this->app['console']->has('demo:echo'));
    }
}
