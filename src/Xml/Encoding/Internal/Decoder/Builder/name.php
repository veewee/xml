<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use \DOM\Node;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function name(\DOM\Node $node): string
{
    return $node->nodeName;
}
