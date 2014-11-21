<?php

namespace Gaufrette\TestSuite\Command;

use Gaufrette\Core\Adapter;
use Gaufrette\TestSuite\Adapter\AdapterFactory;
use Gaufrette\TestSuite\Exception\FailureException;
use Gaufrette\TestSuite\Suite\Registry;
use Gaufrette\TestSuite\Suite\Test;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    /**
     * @var Regitry $tests
     */
    private $tests;

    public function __construct()
    {
        $this->tests = new Registry;

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
            ->addArgument('factory', InputArgument::REQUIRED, 'Your adapter factory class (should implements Gaufrette\TestSuite\Adapter\AdapterFactory)')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getArgument('factory');

        if (false === class_exists($class)) {

            throw new \Exception(sprintf('Class %s not found', $class));
        }

        $rfl = new \ReflectionClass($class);

        if (0 < $rfl->getConstructor()->getNumberOfRequiredParameters()) {

            throw new \Exception('Can\'t instanciate adapter factory %s, constructor shouldn\'t need required parameters');
        }

        $factory = new $class;

        if (false === $factory instanceof AdapterFactory) {

            throw new \Exception('Your adapter factory should implements Gaufrette\TestSuite\Adapter\AdapterFactory');
        }

        $exit = 0;

        foreach ($this->tests->all() as $test) {
            $output->writeln('');
            $adapter = $factory->create();
            $result  = $this->runTest($test, $adapter, $output);

            $exit = true === $result ? $exit : 1;
        }

        return $exit;
    }

    /**
     * Run a test and print it's result
     *
     * @param Gaufrette\TestSuite\Suite\Test $test
     * @param Gaufrette\Core\Adapter $adapter
     * @param Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return boolean
     */
    private function runTest(Test $test, Adapter $adapter, OutputInterface $output)
    {
        $output->write(sprintf('<info>%s : </info>', $test->getSentence()));

        if (false === $test->supports($adapter)) {
            $output->writeln(sprintf('<bg=blue;fg=white> %s </bg=blue;fg=white>', 'UNSUPPORTED'));

            return true;
        }

        try {
            $test->test($adapter);
            $output->writeln(sprintf('<bg=green;fg=white> %s </bg=green;fg=white>', 'OKAY'));

            return true;
        } catch (FailureException $ex) {
            $output->writeln(sprintf('<error> %s </error>', 'ERROR'));
            $output->writeln('');
            $output->writeln($ex->getMessage());

            return false;
        }
    }
}
