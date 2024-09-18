<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use Dom\CDATASection;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert CDATASection $node
 * @throws AssertException
 */
function assert_cdata(mixed $node): CDATASection
{
    return instance_of(CDATASection::class)->assert($node);
}
