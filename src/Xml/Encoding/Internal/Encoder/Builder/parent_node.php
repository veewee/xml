<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use DOMNode;
use DOMElement;
use Psl\Type\Exception\AssertException;
use function VeeWee\Xml\Dom\Builder\children as buildChildren;
use function VeeWee\Xml\Dom\Builder\element as elementBuilder;
use function VeeWee\Xml\Dom\Builder\escaped_value;

/**
 * @param array<int, string|array>|array<string, string|array>|string $data
 * @return callable(DOMNode): DOMElement
 *
 * @throws AssertException
 */
function parent_node(string $name, array|string $data): callable
{
    if (is_string($data)) {
        return buildChildren(elementBuilder($name, escaped_value($data)));
    }

    if (is_node_list($data)) {
        return children($name, $data);
    }

    return buildChildren(element($name, $data));
}
