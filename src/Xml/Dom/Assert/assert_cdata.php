<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \Dom\CDATASection;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \Dom\CDATASection $node
 * @throws AssertException
 */
function assert_cdata(mixed $node): \Dom\CDATASection
{
    return instance_of(\Dom\CDATASection::class)->assert($node);
}
