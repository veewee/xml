<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Node;

use Countable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;

final class NodeSequenceTest extends TestCase
{
    public function test_it_can_be_empty(): void
    {
        $sequence = new NodeSequence();
        static::assertSame([], $sequence->sequence());
        static::assertNull($sequence->parent());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The node sequence is empty. Can not fetch current item!');
        $sequence->current();
    }


    public function test_it_can_not_pop_empty_sequence(): void
    {
        $sequence = new NodeSequence();

        $this->expectException(InvalidArgumentException::class);
        $sequence->pop();
    }

    public function test_it_can_remember_sequences_in_an_immutable_way(): void
    {
        $sequence = new NodeSequence(
            $element1 = new ElementNode(1, 'item1', 'item1', '', '', [])
        );

        static::assertNull($sequence->parent());
        static::assertSame($element1, $sequence->current());
        static::assertSame([$element1], $sequence->sequence());

        $appendedSequence = $sequence->append($element2 = new ElementNode(2, 'item2', 'item2', '', '', []));
        static::assertNotSame($sequence, $appendedSequence);
        static::assertSame($element1, $appendedSequence->parent());
        static::assertSame($element2, $appendedSequence->current());
        static::assertSame([$element1, $element2], $appendedSequence->sequence());

        $removedSequence = $appendedSequence->pop();
        static::assertNotSame($sequence, $removedSequence);
        static::assertNotSame($appendedSequence, $removedSequence);
        static::assertNull($sequence->parent());
        static::assertSame($element1, $removedSequence->current());
        static::assertSame([$element1], $removedSequence->sequence());

        $emptySequence = $removedSequence->pop();
        static::assertNotSame($sequence, $emptySequence);
        static::assertNotSame($appendedSequence, $emptySequence);
        static::assertNotSame($removedSequence, $emptySequence);
        static::assertNull($emptySequence->parent());
        static::assertSame([], $emptySequence->sequence());
    }

    
    public function test_it_can_count_a_sequence(): void
    {
        $sequence = new NodeSequence(
            new ElementNode(1, 'item1', 'item1', '', '', []),
        );

        static::assertInstanceOf(Countable::class, $sequence);
        static::assertCount(1, $sequence);
    }

    
    public function test_it_can_replay_sequence(): void
    {
        $sequence = new NodeSequence(
            $element1 = new ElementNode(1, 'item1', 'item1', '', '', []),
            $element2 = new ElementNode(1, 'item2', 'item2', '', '', []),
        );

        $replayed = [...$sequence->replay()];
        static::assertCount(2, $replayed);
        static::assertEquals(new NodeSequence($element1), $replayed[0]);
        static::assertEquals(new NodeSequence($element1, $element2), $replayed[1]);
    }

    /**
     * Added to keep both infections 'YieldValue' and psalm's non-negative-int happy.
     * Yet it adds little value since it is not allowed to use the sequence like this in psalm.
     * Meh ... :)
     *
     *
     */
    public function test_it_keeps_index_during_yielding(): void
    {
        $sequence = new NodeSequence(
            el1: $element1 = new ElementNode(1, 'item1', 'item1', '', '', []),
            el2: $element2 = new ElementNode(1, 'item2', 'item2', '', '', []),
        );

        $replayed = [...$sequence->replay()];
        static::assertCount(2, $replayed);
        static::assertEquals(new NodeSequence($element1), $replayed['el1']);
        static::assertEquals(new NodeSequence($element1, $element2), $replayed['el2']);
    }

    
    public function test_it_can_slice_node_sequence(): void
    {
        $emptySequence = new NodeSequence();
        static::assertEquals($emptySequence, $emptySequence->slice(0, 100));

        $sequence = new NodeSequence(
            $element1 = new ElementNode(1, 'item1', 'item1', '', '', []),
            $element2 = new ElementNode(1, 'item2', 'item2', '', '', []),
        );

        static::assertEquals($sequence, $sequence->slice(-1));
        static::assertEquals(new NodeSequence($element1), $sequence->slice(-1, 1));
        static::assertEquals(new NodeSequence($element1), $sequence->slice(0, 1));
        static::assertEquals(new NodeSequence($element1, $element2), $sequence->slice(0));
        static::assertEquals(new NodeSequence($element2), $sequence->slice(1, 1));
    }
}
