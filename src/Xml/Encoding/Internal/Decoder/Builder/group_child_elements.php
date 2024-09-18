<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use Dom\Element;
use function VeeWee\Xml\Dom\Locator\Element\children;

/**
 * @psalm-type GroupedElements=array<string, Element|list<Element>>
 * @psalm-internal VeeWee\Xml\Encoding
 * @return GroupedElements
 */
function group_child_elements(Element $element): array
{
    /** @var GroupedElements $grouped */
    $grouped = [];
    foreach (children($element) as $child) {
        $key = name($child);

        if (array_key_exists($key, $grouped)) {
            $data = $grouped[$key];
            $grouped[$key] = is_array($data) ? [...$data, $child] : [$data, $child];
            continue;
        }

        $grouped[$key] = $child;
    }

    return $grouped;
}
