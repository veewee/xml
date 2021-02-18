<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;

/**
 * @return callable(XMLWriter): Generator<bool>
 */
function value(string $value): callable
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($value): Generator {
            yield $writer->text($value);
        };
}
