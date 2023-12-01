<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Opener;

use Closure;
use XMLWriter;

/**
 * @return Closure(XMLWriter): bool XMLWriter
 */
function memory_opener(): Closure
{
    return static function (XMLWriter $writer): bool {
        return $writer->openMemory();
    };
}
