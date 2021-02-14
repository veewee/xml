<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;

/**
 * @param iterable<(callable(XMLWriter): Generator<bool>)> $nodeBuilders
 *
 * @return callable(XMLWriter): Generator<bool>
 */
function nodes(iterable $nodeBuilders): callable
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
