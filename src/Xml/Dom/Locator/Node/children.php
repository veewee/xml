<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use DOMNode;
use DOMNodeList;

function children(DOMNode $node): DOMNodeList
{
    return $node->childNodes;
}
