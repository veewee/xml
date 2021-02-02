<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Node;

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
        $this->expectErrorMessage('The node sequence is empty. Can not fetch current item!');
        $sequence->current();
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
}
