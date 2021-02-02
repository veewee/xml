<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Matcher;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\all;

final class AllTest extends TestCase
{
    public function testIt_returns_true_if_all_matchers_agree(): void
    {
        $matcher = all(
            static fn () => true,
            static fn () => true,
            static fn () => true
        );
        static::assertTrue($matcher($this->createSequence()));
    }

    
    public function testIt_returns_false_if__not_all_matchers_agree(): void
    {
        $matcher = all(
            static fn () => true,
            static fn () => true,
            static fn () => false
        );
        static::assertFalse($matcher($this->createSequence()));
    }

    
    public function testIt_returns_true_if_there_are_no_matchers(): void
    {
        $matcher = all();
        static::assertTrue($matcher($this->createSequence()));
    }

    private function createSequence(): NodeSequence
    {
        return new NodeSequence();
    }
}
