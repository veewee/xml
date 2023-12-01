<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Closure;
use Generator;
use XMLWriter;

/**
 * @return Closure(XMLWriter): Generator<bool>
 */
function namespaced_attribute(string $namespace, ?string $prefix, string $name, string $value): Closure
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($namespace, $prefix, $name, $value): Generator {
            yield $writer->startAttributeNs($prefix, $name, $namespace);
            yield $writer->text($value);
            yield $writer->endAttribute();
        };
}
