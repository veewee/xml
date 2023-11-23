<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use Closure;
use DOMElement;
use DOMNode;
use Psl\Exception\InvariantViolationException;
use Psl\Type\Exception\AssertException;
use function VeeWee\Xml\Dom\Builder\children as buildChildren;
use function VeeWee\Xml\Dom\Builder\element as elementBuilder;
use function VeeWee\Xml\Dom\Builder\escaped_value;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 * @psalm-suppress LessSpecificReturnStatement, MoreSpecificReturnType
 *
 * @return Closure(DOMNode): DOMElement
 *
 * @throws AssertException
 * @throws InvariantViolationException
 */
function parent_node(string $name, array|string $data): Closure
{
    if (is_string($data)) {
        return buildChildren(elementBuilder($name, escaped_value($data)));
    }

    if (is_node_list($data)) {
        return children($name, $data);
    }

    return buildChildren(element($name, $data));
}
