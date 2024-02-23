<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \DOM\CDATASection;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \DOM\CDATASection $node
 * @throws AssertException
 */
function assert_cdata(mixed $node): \DOM\CDATASection
{
    return instance_of(\DOM\CDATASection::class)->assert($node);
}
