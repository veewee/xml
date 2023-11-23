<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Closure;
use Generator;
use XMLWriter;

/**
 * @param array<string, string> $attributes
 * @return Closure(XMLWriter): Generator<bool>
 */
function attributes(array $attributes): Closure
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($attributes): Generator {
            foreach ($attributes as $key => $value) {
                yield from attribute($key, $value)($writer);
            }
        };
}
