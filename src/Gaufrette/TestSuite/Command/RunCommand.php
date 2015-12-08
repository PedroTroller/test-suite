<?php

namespace Gaufrette\TestSuite\Command;

use Gaufrette\Core\Adapter;
use Gaufrette\TestSuite\Adapter\AdapterFactory;
use Gaufrette\TestSuite\Suite\Registry;
use Gaufrette\TestSuite\Suite\Test;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    /**
     * @type Registry
     */
    private $tests;

    public function __construct()
    {
        $this->tests = new Registry();

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Launch all suites')
            ->addArgument('factory', InputArgument::REQUIRED, 'Your adapter factory class (should implements Gaufrette\TestSuite\Adapter\AdapterFactory)');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getArgument('factory');
        $class = str_replace('/', '\\', $class);

        if (false === class_exists($class)) {
            throw new \Exception(sprintf('Class %s not found', $class));
        }

        $rfl = new \ReflectionClass($class);

        if (null !== $rfl->getConstructor() && 0 < $rfl->getConstructor()->getNumberOfRequiredParameters()) {
            throw new \Exception('Can\'t instanciate adapter factory %s, constructor shouldn\'t need required parameters');
        }

        $factory = new $class();

        if (false === $factory instanceof AdapterFactory) {
            throw new \Exception('Your adapter factory should implements Gaufrette\TestSuite\Adapter\AdapterFactory');
        }

        $exit = 0;

        foreach ($this->tests->all() as $test) {
            $output->writeln('');
            $adapter = $factory->create();
            $result  = $this->runTest($test, $adapter, $output);
            $factory->destroy();

            $exit = true === $result ? $exit : 1;
        }
        $output->writeln('');

        return $exit;
    }

    /**
     * Run a test and print it's result.
     *
     * @param Test            $test
     * @param Adapter         $adapter
     * @param OutputInterface $output
     *
     * @return bool
     */
    private function runTest(Test $test, Adapter $adapter, OutputInterface $output)
    {
        $output->write(sprintf('<info>%s: </info>', $test->getSentence()));

        if (false === $test->supports($adapter)) {
            $output->writeln(sprintf('<bg=blue;fg=white> %s </bg=blue;fg=white>', 'UNSUPPORTED'));

            return true;
        }

        try {
            $test->run($adapter);
            $output->writeln(sprintf('<bg=green;fg=black> %s </bg=green;fg=black>', 'OKAY'));

            return true;
        } catch (\Exception $ex) {
            $output->writeln(sprintf('<error> %s </error>', 'ERROR'));
            $output->writeln('');
            $output->writeln(sprintf('<fg=red> %s </fg=red>', $ex->getMessage()));

            return false;
        }
    }
}
