<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Node;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\AttributeNode;

final class AttributeNodeTest extends TestCase
{
    public function test_it_contains_xml_attribute_information(): void
    {
        $attribute = new AttributeNode(
            $name = 'name',
            $localName = 'localName',
            $namespace = 'https://namespace',
            $namespacePrefix = 'prefix',
            $value = 'hello'
        );

        static::assertSame($name, $attribute->name());
        static::assertSame($localName, $attribute->localName());
        static::assertSame($namespace, $attribute->namespace());
        static::assertSame($namespacePrefix, $attribute->namespaceAlias());
        static::assertSame($value, $attribute->value());
    }
}
