<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;

/**
 * @return callable(XMLWriter): Generator<bool>
 */
function attribute(string $name, string $value): callable
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($name, $value): Generator {
            yield $writer->startAttribute($name);
            yield $writer->text($value);
            yield $writer->endAttribute();
        };
}
