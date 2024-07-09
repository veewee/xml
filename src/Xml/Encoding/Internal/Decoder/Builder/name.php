<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use \Dom\Node;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function name(\Dom\Node $node): string
{
    return $node->nodeName;
}
