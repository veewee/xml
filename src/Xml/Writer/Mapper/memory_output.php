<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Mapper;

use Closure;
use XMLWriter;

/**
 * @return Closure(XMLWriter): string XMLWriter
 */
function memory_output(): Closure
{
    return static function (XMLWriter $writer): string {
        return $writer->outputMemory();
    };
}
