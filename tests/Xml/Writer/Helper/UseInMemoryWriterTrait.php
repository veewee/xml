<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Helper;

use XMLWriter;

trait UseInMemoryWriterTrait
{
    /**
     * @param callable(XMLWriter): void $run
     */
    private function runInMemory(callable $run): string
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();

        $run($xmlWriter);

        return $xmlWriter->outputMemory();
    }
}
