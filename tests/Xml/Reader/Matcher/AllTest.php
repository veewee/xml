<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Matcher;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\all;

class AllTest extends TestCase
{
    /** @test */
    public function it_returns_true_if_all_matchers_agree(): void
    {
        $matcher = all(
            static fn () => true,
            static fn () => true,
            static fn () => true
        );
        self::assertTrue($matcher($this->createSequence()));
    }

    /** @test */
    public function it_returns_false_if__not_all_matchers_agree(): void
    {
        $matcher = all(
            static fn () => true,
            static fn () => true,
            static fn () => false
        );
        self::assertFalse($matcher($this->createSequence()));
    }

    /** @test */
    public function it_returns_true_if_there_are_no_matchers(): void
    {
        $matcher = all();
        self::assertTrue($matcher($this->createSequence()));
    }

    private function createSequence(): NodeSequence
    {
        return new NodeSequence();
    }
}
