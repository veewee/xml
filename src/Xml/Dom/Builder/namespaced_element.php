<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \DOM\Element;
use \DOM\Node;
use function VeeWee\Xml\Dom\Assert\assert_element;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\Internal\configure;

/**
 * @param list<callable(\DOM\Element): \DOM\Element> $configurators
 *
 * @return Closure(\DOM\Node): \DOM\Element
 */
function namespaced_element(string $namespace, string $qualifiedName, callable ...$configurators): Closure
{
    return static function (\DOM\Node $node) use ($namespace, $qualifiedName, $configurators): \DOM\Element {
        $document = detect_document($node);

        return assert_element(
            configure(...$configurators)($document->createElementNS($namespace, $qualifiedName))
        );
    };
}
