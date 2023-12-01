<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Helper;

use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Mapper\memory_output;

trait UseInMemoryWriterTrait
{
    /**
     * @param callable(XMLWriter): void $run
     */
    private function runInMemory(callable $run): string
    {
        return Writer::inMemory()
            ->apply($run)
            ->map(memory_output());
    }
}
