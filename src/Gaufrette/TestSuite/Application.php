<?php

namespace Gaufrette\TestSuite;

use Gaufrette\TestSuite\Command\RunCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $input  = $input  ?: new ArgvInput();
        $output = $output ?: new ConsoleOutput();

        $this->add(new RunCommand);

        $this->configureIO($input, $output);

        parent::run($input, $output);
    }
}
