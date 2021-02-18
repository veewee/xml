<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Manipulator\append;

final class AppendTest extends TestCase
{
    public function test_it_can_append_stuff_to_a_document(): void
    {
        $doc = Document::empty();
        $doc->manipulate(
            append(
                ...$doc->build(
                    element('root'),
                )
            )
        );

        static::assertXmlStringEqualsXmlString('<root />', $doc->toXmlString());
    }

    
    public function test_it_can_append_stuff_to_a_any_node(): void
    {
        $doc = Document::fromXmlString('<root />');
        append(
            ...$doc->build(
                element('item1'),
                element('item2'),
            )
        )($doc->toUnsafeDocument()->documentElement);

        static::assertXmlStringEqualsXmlString('<root><item1 /><item2 /></root>', $doc->toXmlString());
    }
}
