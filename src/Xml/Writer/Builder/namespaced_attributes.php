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
function namespaced_attributes(string $namespace, array $attributes): Closure
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($namespace, $attributes): Generator {
            foreach ($attributes as $key => $value) {
                $parts = explode(':', $key, 2);
                $name = array_pop($parts);
                $prefix = $parts ? $parts[0] : null;

                yield from namespaced_attribute($namespace, $prefix, $name, $value)($writer);
            }
        };
}
