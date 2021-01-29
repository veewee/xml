<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Node;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\AttributeNode;

class AttributeNodeTest extends TestCase
{
    /** @test */
    public function it_contains_xml_attribute_information(): void
    {
        $attribute = new AttributeNode(
            $name = 'name',
            $localName = 'localName',
            $namespace = 'https://namespace',
            $namespacePrefix = 'prefix',
            $value = 'hello'
        );

        self::assertSame($name, $attribute->name());
        self::assertSame($localName, $attribute->localName());
        self::assertSame($namespace, $attribute->namespace());
        self::assertSame($namespacePrefix, $attribute->namespaceAlias());
        self::assertSame($value, $attribute->value());
    }
}
