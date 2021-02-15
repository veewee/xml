<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;
use function VeeWee\Xml\Assertion\assert_strict_prefixed_name;

/**
 * @param list<(callable(XMLWriter): Generator<bool>)> $configurators
 *
 * @return callable(XMLWriter): Generator<bool>
 */
function namespaced_element(string $namespace, string $qualifiedName, callable ...$configurators): callable
{
    return
        /**
         * @return Generator<bool>
         */
        static function (XMLWriter $writer) use ($namespace, $qualifiedName, $configurators): Generator {
            assert_strict_prefixed_name($qualifiedName);
            [$prefix, $name] = explode(':', $qualifiedName);

            yield $writer->startElementNs($prefix, $name, $namespace);
            foreach ($configurators as $configurator) {
                yield from $configurator($writer);
            }

            yield $writer->endElement();
        };
}
