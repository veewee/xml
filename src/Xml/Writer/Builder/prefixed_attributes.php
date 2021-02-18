<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;
use function VeeWee\Xml\Assertion\assert_strict_prefixed_name;

/**
 * @param array<string, string> $attributes
 * @return callable(XMLWriter): Generator<bool>
 */
function prefixed_attributes(array $attributes): callable
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($attributes): Generator {
            foreach ($attributes as $key => $value) {
                assert_strict_prefixed_name($key);
                [$prefix, $name] = explode(':', $key);

                yield from prefixed_attribute($prefix, $name, $value)($writer);
            }
        };
}
