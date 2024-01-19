<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Closure;
use Generator;
use XMLWriter;

/**
 * @param non-empty-string|null $prefix
 * @return Closure(XMLWriter): Generator<bool>
 */
function namespace_attribute(string $namespace, ?string $prefix = null): Closure
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($namespace, $prefix): Generator {
            if ($prefix !== null) {
                yield from prefixed_attribute('xmlns', $prefix, $namespace)($writer);
                return;
            }

            yield from attribute('xmlns', $namespace)($writer);
        };
}
