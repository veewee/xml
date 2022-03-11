<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use DOMDocument;
use DOMNode;
use Psl\Type\Exception\AssertException;
use function Psl\Type\object;

/**
 * @psalm-assert DOMDocument $node
 * @throws AssertException
 */
function assert_document(?DOMNode $node): DOMDocument
{
    return object(DOMDocument::class)->assert($node);
}
