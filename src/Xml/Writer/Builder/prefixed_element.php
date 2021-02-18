<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;

/**
 * @param list<(callable(XMLWriter): Generator<bool>)> $configurators
 *
 * @return callable(XMLWriter): Generator<bool>
 */
function prefixed_element(string $prefix, string $name, callable ...$configurators): callable
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($prefix, $name, $configurators): Generator {
            yield $writer->startElementNs($prefix, $name, null);
            foreach ($configurators as $configurator) {
                yield from $configurator($writer);
            }

            yield $writer->endElement();
        };
}
