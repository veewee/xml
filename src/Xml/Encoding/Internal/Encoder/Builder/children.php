<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use Closure;
use Dom\Element;
use function Psl\Dict\map;
use function VeeWee\Xml\Dom\Builder\children as buildChildren;
use function VeeWee\Xml\Dom\Builder\element as elementBuilder;
use function VeeWee\Xml\Dom\Builder\value;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 *
 * @psalm-suppress LessSpecificReturnStatement, MoreSpecificReturnType
 *
 * @return Closure(Element): Element
 */
function children(string $name, array $children): Closure
{
    return buildChildren(
        ...map(
            $children,
            /**
             * @return Closure(Element): Element
             */
            static fn (array|string $data): Closure => is_array($data)
                ? element($name, $data)
                : elementBuilder($name, value($data))
        )
    );
}
