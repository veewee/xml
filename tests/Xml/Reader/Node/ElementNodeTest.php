<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Node;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;

final class ElementNodeTest extends TestCase
{
    public function testIt_contains_xml_element_information(): void
    {
        $element = new ElementNode(
            $position = 1,
            $name = 'name',
            $localName = 'localName',
            $namespace = 'https://namespace',
            $namespacePrefix = 'prefix',
            $attributes = [
                new AttributeNode(
                    $name = 'name',
                    $localName = 'localName',
                    $namespace = 'https://namespace',
                    $namespacePrefix = 'prefix',
                    $value = 'hello'
                )
            ]
        );

        static::assertSame($position, $element->position());
        static::assertSame($name, $element->name());
        static::assertSame($localName, $element->localName());
        static::assertSame($namespace, $element->namespace());
        static::assertSame($namespacePrefix, $element->namespaceAlias());
        static::assertSame($attributes, $element->attributes());
    }
}
