<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;

/**
 * @return callable(XMLWriter): Generator<bool>
 */
function prefixed_attribute(string $prefix, string $name, string $value): callable
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($prefix, $name, $value): Generator {
            yield $writer->startAttributeNs($prefix, $name, null);
            yield $writer->text($value);
            yield $writer->endAttribute();
        };
}
