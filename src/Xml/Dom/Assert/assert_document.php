<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \DOM\XMLDocument;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \DOM\XMLDocument $node
 * @throws AssertException
 */
function assert_document(mixed $node): \DOM\XMLDocument
{
    return instance_of(\DOM\XMLDocument::class)->assert($node);
}
