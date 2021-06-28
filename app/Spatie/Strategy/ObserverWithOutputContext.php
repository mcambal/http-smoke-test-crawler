<?php

namespace App\Spatie\Strategy;

use App\Output\Context\OutputContext;

interface ObserverWithOutputContext
{
    /**
     * @param OutputContext $outputContext
     */
    public function setOutputContext(OutputContext $outputContext): void;
}
