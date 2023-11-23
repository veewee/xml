<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Closure;
use Generator;
use XMLWriter;

/**
 * @param list<(callable(XMLWriter): Generator<bool>)> $configurators
 *
 * @return Closure(XMLWriter): Generator<bool>
 */
function document(string $version, string $charset, callable ... $configurators): Closure
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($version, $charset, $configurators): Generator {
            yield $writer->startDocument($version, $charset);
            foreach ($configurators as $configurator) {
                yield from $configurator($writer);
            }
            yield $writer->endDocument();
        };
}
