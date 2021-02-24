<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use DOMElement;
use function Psl\Dict\map;
use function VeeWee\Xml\Dom\Builder\children as buildChildren;
use function VeeWee\Xml\Dom\Builder\element as elementBuilder;
use function VeeWee\Xml\Dom\Builder\value;

/**
 * @psalm-suppress LessSpecificReturnStatement, MoreSpecificReturnType
 *
 * @param array<array-key, string|array> $children
 *
 * @return callable(DOMElement): DOMElement
 */
function children(string $name, array $children): callable
{
    return buildChildren(
        ...map(
            $children,
            /**
             * @param string|array<string, string|array> $data
             * @return callable(DOMElement): DOMElement
             */
            static fn (array|string $data): callable => is_array($data)
                ? element($name, $data)
                : elementBuilder($name, value($data))
        )
    );
}
