<?php

namespace Gaufrette\TestSuite\Adapter;

interface AdapterFactory
{
    /**
     * Build your adapter.
     *
     * @return Gaufrette\Core\Adapter
     */
    public function create();

    /**
     * Call after tests.
     */
    public function destroy();
}
