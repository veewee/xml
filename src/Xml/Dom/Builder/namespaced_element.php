<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \Dom\Element;
use \Dom\Node;
use function VeeWee\Xml\Dom\Assert\assert_element;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\Internal\configure;

/**
 * @param list<callable(\Dom\Element): \Dom\Element> $configurators
 *
 * @return Closure(\Dom\Node): \Dom\Element
 */
function namespaced_element(string $namespace, string $qualifiedName, callable ...$configurators): Closure
{
    return static function (\Dom\Node $node) use ($namespace, $qualifiedName, $configurators): \Dom\Element {
        $document = detect_document($node);

        return assert_element(
            configure(...$configurators)($document->createElementNS($namespace, $qualifiedName))
        );
    };
}
