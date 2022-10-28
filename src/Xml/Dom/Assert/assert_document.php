<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use DOMDocument;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert DOMDocument $node
 * @throws AssertException
 */
function assert_document(mixed $node): DOMDocument
{
    return instance_of(DOMDocument::class)->assert($node);
}
