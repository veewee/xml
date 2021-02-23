<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use function Psl\Dict\map;
use function VeeWee\Xml\Dom\Builder\children as buildChildren;

function children(string $name, array $children): callable
{
    return buildChildren(
        ...map(
            $children,
            static fn (array $data): callable => element($name, $data)
        )
    );
}
