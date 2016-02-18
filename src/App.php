<?php

/*
 * This file is part of `lemonphp/cli` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Cli;

use Lemon\Cli\Console\Command\Command;
use Lemon\Cli\Provider\ConsoleServiceProvider;
use Lemon\Cli\Provider\DispatcherServiceProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Container main class
 */
class App extends Container
{
    /**
     * @var boolean
     */
    protected $booted = false;

    /**
     * @var ServiceProviderInterface[]
     */
    protected $providers = [];

    /**
     * Contructor
     * Registers the autoloader and necessary components.
     *
     * @param string      $name    Name for this application.
     * @param string|null $version Version number for this application.
     * @param array       $values  The parameters or objects.
     */
    public function __construct($name, $version = null, array $values = [])
    {
        parent::__construct($values);

        $this->register(new DispatcherServiceProvider());
        $this->register(new ConsoleServiceProvider(), [
            'console.name'    => $name,
            'console.version' => $version,
        ]);
    }

    /**
     * Registers a service provider.
     *
     * @param \Pimple\ServiceProviderInterface $provider A ServiceProviderInterface instance
     * @param array                            $values   An array of values that customizes the provider
     * @return \Pimple\Container
     */
    public function register(ServiceProviderInterface $provider, array $values = [])
    {
        parent::register($provider, $values);
        $this->providers[] = $provider;

        return $this;
    }

    /**
     * Boots the Application by calling boot on every provider added and then subscribe
     * in order to add listeners.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

        $this->booted = true;
        foreach ($this->providers as $provider) {
            if ($provider instanceof EventSubscriberInterface) {
                $this['dispatcher']->addSubscriber($provider);
            }
        }
    }

    /**
     * Executes this application
     *
     * @param \Symfony\Component\Console\Input\InputInterface|null   $input
     * @param \Symfony\Component\Console\Output\OutputInterface|null $output
     * @return int
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->boot();

        return $this['console']->run($input, $output);
    }

    /**
     * Add a command
     * If a command with the same name already exists, it will be overridden.
     */
    public function addCommand(Command $command)
    {
        $this['console']->add($command);
    }
}
