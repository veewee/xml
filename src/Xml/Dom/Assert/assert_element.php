<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use DOMElement;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert DOMElement $node
 * @throws AssertException
 */
function assert_element(mixed $node): DOMElement
{
    return instance_of(DOMElement::class)->assert($node);
}
