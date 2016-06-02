<?php

/*
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli\Tests\Console\Command;

use Pimple\Container;
use Symfony\Component\Console\Application;
use Lemon\Cli\Console\ContainerAwareApplication;

/**
 * Test GreetCommand
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test `getContainer()` method
     */
    public function testGetContainer()
    {
        $command = $this->getMockForAbstractClass(\Lemon\Cli\Console\Command\Command::class, ['foo']);
        $container = new Container();
        $application = new ContainerAwareApplication();

        // application is not instance of ContainerAwareInterface
        $command->setApplication(new Application());
        $this->assertNull($command->getContainer());

        // application is instance of ContainerAwareInterface but didn't set container
        $command->setApplication($application);
        $this->assertNull($command->getContainer());

        // application is instance of ContainerAwareInterface and setted container
        $application->setContainer($container);
        $this->assertSame($container, $command->getContainer());
    }

    /**
     * Test `getService()` method
     * @dataProvider dataTestGetService
     */
    public function testGetService($container, $serviceName, $returnType)
    {
        
        $command = $this->getMockBuilder(\Lemon\Cli\Console\Command\Command::class)
            ->setMethods(['getContainer'])
            ->disableOriginalConstructor()
            ->getMock();

        $command->method('getContainer')->willReturn($container);

        if (is_null($container)) {
            $this->assertNull($command->getContainer());
        } else {
            $this->assertSame($container, $command->getContainer());
        }

        if (is_null($returnType)) {
            $this->assertNull($command->getService($serviceName));
        } else {
            $this->assertInstanceOf($returnType, $command->getService($serviceName));
//            $this->assertInstanceOf($returnType, $command->getContainer()[$serviceName]);
//            $this->assertInstanceOf($returnType, $container[$serviceName]);
        }
    }

    /**
     * Data for test `getService()` method
     * @return array
     */
    public function dataTestGetService()
    {
        $container = new Container([
            'now' => function () {
                return new \DateTime();
            }
        ]);

        return [
            [null, 'bar', null],
            [$container, 'bar', null],
            [$container, 'now', \DateTime::class],
        ];
    }
}
