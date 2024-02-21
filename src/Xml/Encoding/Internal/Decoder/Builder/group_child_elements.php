<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use \DOM\Element;
use function VeeWee\Xml\Dom\Locator\Element\children;

/**
 * @psalm-type GroupedElements=array<string, \DOM\Element|list<\DOM\Element>>
 * @psalm-internal VeeWee\Xml\Encoding
 * @return GroupedElements
 */
function group_child_elements(\DOM\Element $element): array
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
