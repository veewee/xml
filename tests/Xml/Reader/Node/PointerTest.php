<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Node;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use VeeWee\Xml\Reader\Node\Pointer;

final class PointerTest extends TestCase
{
    public function test_it_is_empty_at_the_start(): void
    {
        $pointer = Pointer::create();

        static::assertSame(1, $pointer->getNextSiblingPosition());
        static::assertSame(0, $pointer->getDepth());
        static::assertEquals(new NodeSequence(), $pointer->getNodeSequence());
    }

    
    public function test_it_cannot_leave_element_on_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('Currently at root level. Can not leave element!');

        $pointer = Pointer::create();
        $pointer->leaveElement();
    }

    
    public function test_it_knows_the_position_on_enter(): void
    {
        $pointer = Pointer::create();

        $nextPos = $pointer->getNextSiblingPosition();
        $pointer->enterElement($element1 = new ElementNode($nextPos, 'item', 'item', '', '', []));
        static::assertSame(1, $pointer->getDepth());
        static::assertSame(1, $element1->position());
        static::assertEquals(new NodeSequence($element1), $pointer->getNodeSequence());

        $nextPos = $pointer->getNextSiblingPosition();
        $pointer->enterElement($element2 = new ElementNode($nextPos, 'item', 'item', '', '', []));
        static::assertSame(2, $pointer->getDepth());
        static::assertSame(1, $element2->position());
        static::assertEquals(new NodeSequence($element1, $element2), $pointer->getNodeSequence());
        $pointer->leaveElement();

        $nextPos = $pointer->getNextSiblingPosition();
        $pointer->enterElement($element3 = new ElementNode($nextPos, 'item', 'item', '', '', []));
        static::assertSame(2, $pointer->getDepth());
        static::assertSame(2, $element3->position());
        static::assertEquals(new NodeSequence($element1, $element3), $pointer->getNodeSequence());
        $pointer->leaveElement();

        $pointer->leaveElement();
        static::assertSame(2, $pointer->getNextSiblingPosition());
        static::assertSame(0, $pointer->getDepth());
        static::assertEquals(new NodeSequence(), $pointer->getNodeSequence());
    }
}
