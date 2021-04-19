<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMElement;
use function VeeWee\Xml\Dom\Locator\Node\children;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 * @return array<string, DOMElement|list<DOMElement>>
 */
function group_child_elements(DOMElement $element): array
{
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
