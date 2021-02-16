<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;

/**
 * @return callable(XMLWriter): Generator<bool>
 */
function namespace_attribute(string $namespace, ?string $prefix): callable
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($namespace, $prefix): Generator {
            if ($prefix) {
                yield from prefixed_attribute('xmlns', $prefix, $namespace)($writer);
                return;
            }

            yield from attribute('xmlns', $namespace)($writer);
        };
}