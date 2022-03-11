<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use DOMElement;
use DOMNode;
use Psl\Type\Exception\AssertException;
use function Psl\Type\object;

/**
 * @psalm-assert DOMElement $node
 * @throws AssertException
 */
function assert_element(?DOMNode $node): DOMElement
{
    return object(DOMElement::class)->assert($node);
}
