<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\Core\Adapter\Configuration;

class Configuration extends AbstractTest
{
    /**
     * {@inheritdoc}
     */
    public function supports(Adapter $adapter)
    {
        return $this->adapterHasBehavior($adapter, 'Gaufrette\Core\Adapter\HasConfiguration');
    }

    /**
     * {@inheritdoc}
     */
    public function run(Adapter $adapter)
    {
        $class = get_class($adapter);
        $configuration = sprintf('%s\Configuration', $class);

        if (false === class_exists($configuration)) {
            throw new \Exception(sprintf(
                'This adapter is configurable, configuration details should be located into the %s class',
                $configuration
            ));
        }

        $instance = new $configuration();

        if (false === $instance instanceof Configuration) {
            throw new \Exception(sprintf(
                'Configuration %s have to be an instance of Configuration',
                $configuration,
                'Gaufrette\Core\Adapter\Configuration'
            ));
        }

        if (false === is_array($instance->getRequiredOptions())) {
            throw new \Exception(sprintf(
                '%s::getRequiredOptions() should return an array, %s given.',
                $configuration,
                gettype($instance->getRequiredOptions())
            ));
        }

        if (false === is_array($instance->getOptionalOptions())) {
            throw new \Exception(sprintf(
                '%s::getOptionalOptions() should return an array, %s given.',
                $configuration,
                gettype($instance->getOptionalOptions())
            ));
        }

        $intersect = array_intersect($instance->getRequiredOptions(), $instance->getOptionalOptions());

        if (false === empty($intersect)) {
            throw new \Exception(sprintf('Options [%s] are both optional and required.', explode(', '$intersect)));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSentence()
    {
        return 'Adapter configuration';
    }
}
