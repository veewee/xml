<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Node;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;

class NodeSequenceTest extends TestCase
{
    /** @test */
    public function it_can_be_empty(): void
    {
        $sequence = new NodeSequence();
        self::assertSame([], $sequence->sequence());
        self::assertNull($sequence->parent());

        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('The node sequence is empty. Can not fetch current item!');
        $sequence->current();
    }

    /** @test */
    public function it_can_remember_sequences_in_an_immutable_way(): void
    {
        $sequence = new NodeSequence(
            $element1 = new ElementNode(1, 'item1', 'item1', '', '', [])
        );

        self::assertNull($sequence->parent());
        self::assertSame($element1, $sequence->current());
        self::assertSame([$element1], $sequence->sequence());

        $appendedSequence = $sequence->append($element2 = new ElementNode(2, 'item2', 'item2', '', '', []));
        self::assertNotSame($sequence, $appendedSequence);
        self::assertSame($element1, $appendedSequence->parent());
        self::assertSame($element2, $appendedSequence->current());
        self::assertSame([$element1, $element2], $appendedSequence->sequence());

        $removedSequence = $appendedSequence->pop();
        self::assertNotSame($sequence, $removedSequence);
        self::assertNotSame($appendedSequence, $removedSequence);
        self::assertNull($sequence->parent());
        self::assertSame($element1, $removedSequence->current());
        self::assertSame([$element1], $removedSequence->sequence());

        $emptySequence = $removedSequence->pop();
        self::assertNotSame($sequence, $emptySequence);
        self::assertNotSame($appendedSequence, $emptySequence);
        self::assertNotSame($removedSequence, $emptySequence);
        self::assertNull($emptySequence->parent());
        self::assertSame([], $emptySequence->sequence());
    }
}
