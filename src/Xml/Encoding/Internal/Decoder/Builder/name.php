<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMNode;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function name(DOMNode $node): string
{
    return $node->nodeName;
}
