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

use Symfony\Component\Console\Tester\CommandTester;
use Lemon\Cli\Console\Command\GreetCommand;

/**
 * Test GreetCommand
 */
class GreetCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test `configure` method
     */
    public function testConfigure()
    {
        $command = new GreetCommand();

        $this->assertSame('demo:greet', $command->getName());
        $this->assertSame('Greet someone', $command->getDescription());
        $this->assertTrue($command->getDefinition()->hasArgument('name'));
        $this->assertTrue($command->getDefinition()->hasOption('yell'));
    }

    /**
     * @dataProvider dataForTestExecute
     */
    public function testExecute($name, $yell, $expected)
    {
        $command = new GreetCommand();
        $tester = new CommandTester($command);

        $tester->execute(['name' => $name, '--yell' => $yell]);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertSame($expected, $tester->getDisplay());
    }

    /**
     * Data for test `execute()` method
     *
     * @return array
     */
    public function dataForTestExecute()
    {
        return [
            [null, false, 'Hello' . PHP_EOL],
            ['LemonPHP', false, 'Hello LemonPHP' . PHP_EOL],
            ['LemonPHP', true, 'HELLO LEMONPHP' . PHP_EOL],
        ];
    }
}
