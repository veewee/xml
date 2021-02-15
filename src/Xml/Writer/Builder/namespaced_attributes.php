<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;

/**
 * @param array<string, string> $attributes
 * @return callable(XMLWriter): Generator<bool>
 */
function namespaced_attributes(string $namespace, array $attributes): callable
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($namespace, $attributes): Generator {
            foreach ($attributes as $key => $value) {
                yield from namespaced_attribute($namespace, $key, $value)($writer);
            }
        };
}
