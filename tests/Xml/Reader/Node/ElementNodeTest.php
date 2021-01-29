<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Node;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;

class ElementNodeTest extends TestCase
{
    /** @test */
    public function it_contains_xml_element_information(): void
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

        self::assertSame($position, $element->position());
        self::assertSame($name, $element->name());
        self::assertSame($localName, $element->localName());
        self::assertSame($namespace, $element->namespace());
        self::assertSame($namespacePrefix, $element->namespaceAlias());
        self::assertSame($attributes, $element->attributes());
    }
}
