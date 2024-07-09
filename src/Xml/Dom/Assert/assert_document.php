<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \Dom\XMLDocument;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \Dom\XMLDocument $node
 * @throws AssertException
 */
function assert_document(mixed $node): \Dom\XMLDocument
{
    return instance_of(\Dom\XMLDocument::class)->assert($node);
}
