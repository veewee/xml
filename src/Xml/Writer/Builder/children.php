<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Closure;
use Generator;
use XMLWriter;

/**
 * @param iterable<(callable(XMLWriter): Generator<bool>)> $nodeBuilders
 *
 * @return Closure(XMLWriter): Generator<bool>
 */
function children(iterable $nodeBuilders): Closure
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($nodeBuilders): Generator {
            foreach ($nodeBuilders as $nodeBuilder) {
                yield from $nodeBuilder($writer);
            }
        };
}
