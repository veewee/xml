<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \DOM\CdataSection;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \DOM\CdataSection $node
 * @throws AssertException
 */
function assert_cdata(mixed $node): \DOM\CdataSection
{
    return instance_of(\DOM\CdataSection::class)->assert($node);
}
