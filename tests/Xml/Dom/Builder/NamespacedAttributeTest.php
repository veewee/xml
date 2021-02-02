<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Builder;

use DOMDocument;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\namespaced_attribute;

final class NamespacedAttributeTest extends TestCase
{
    public function testIt_throws_exception_if_the_attribute_name_is_not_qualified(): void
    {
        $doc = new DOMDocument();
        $ns = 'https://namespace.com';

        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('The provided value "key1" is not a QName, expected ns:name instead');

        element(
            'hello',
            namespaced_attribute($ns, 'key1', 'nsvalue1'),
        )($doc);
    }
}
