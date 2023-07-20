<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use DOMCdataSection;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert DOMCdataSection $node
 * @throws AssertException
 */
function assert_cdata(mixed $node): DOMCdataSection
{
    return instance_of(DOMCdataSection::class)->assert($node);
}
