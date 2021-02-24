<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use function Psl\Dict\map;
use function VeeWee\Xml\Dom\Builder\children as buildChildren;
use function VeeWee\Xml\Dom\Builder\element as elementBuilder;
use function VeeWee\Xml\Dom\Builder\value;

function children(string $name, array $children): callable
{
    return buildChildren(
        ...map(
            $children,
            static fn (array|string $data): callable => is_array($data) ? element($name, $data) : elementBuilder($name, value($data))
        )
    );
}
