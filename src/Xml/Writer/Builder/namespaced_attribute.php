<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;
use function VeeWee\Xml\Assertion\assert_strict_prefixed_name;

/**
 * @return callable(XMLWriter): Generator<bool>
 */
function namespaced_attribute(string $namespace, string $qualifiedName, string $value): callable
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($namespace, $qualifiedName, $value): Generator {
            assert_strict_prefixed_name($qualifiedName);
            [$prefix, $name] = explode(':', $qualifiedName);

            yield $writer->startAttributeNs($prefix, $name, $namespace);
            yield $writer->text($value);
            yield $writer->endAttribute();
        };
}
