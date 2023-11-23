<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Closure;
use Generator;
use XMLWriter;

/**
 * @return Closure(XMLWriter): Generator<bool>
 */
function value(string $value): Closure
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($value): Generator {
            yield $writer->text($value);
        };
}
