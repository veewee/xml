<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use Dom\XMLDocument;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert XMLDocument $node
 * @throws AssertException
 */
function assert_document(mixed $node): XMLDocument
{
    return instance_of(XMLDocument::class)->assert($node);
}
