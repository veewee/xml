<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMElement;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 * @return array<string, DOMElement|array<DOMElement>>
 */
function group_child_elements(DOMElement $element): array
{
    $grouped = [];
    foreach ($element->childNodes as $child) {
        if (!$child instanceof DOMElement) {
            continue;
        }

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
