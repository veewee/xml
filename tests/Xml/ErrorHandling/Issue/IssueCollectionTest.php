<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\ErrorHandling\Issue;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\ErrorHandling\Issue\Issue;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\ErrorHandling\Issue\Level;

final class IssueCollectionTest extends TestCase
{
    use UseIssueTrait;

    public function test_it_acts_as_an_iterator(): void
    {
        $issues = new IssueCollection(
            $issue1 = $this->createIssue(Level::warning()),
            $issue2 = $this->createIssue(Level::fatal()),
        );

        static::assertCount(2, $issues);
        static::assertSame([$issue1, $issue2], [...$issues]);
    }

    public function test_it_can_convert_to_string(): void
    {
        $issues = new IssueCollection(
            $issue1 = $this->createIssue(Level::warning()),
            $issue2 = $this->createIssue(Level::fatal()),
        );

        $expected = implode(PHP_EOL, [
            '[WARNING] file.xml: message (1) on line 99,10',
            '[FATAL] file.xml: message (1) on line 99,10',
        ]);

        static::assertSame($expected, $issues->toString());
    }

    public function test_it_can_filter(): void
    {
        $issues = new IssueCollection(
            $issue1 = $this->createIssue(Level::warning()),
            $issue2 = $this->createIssue(Level::fatal()),
        );
        $filtered = $issues->filter(static fn (Issue $issue): bool => !$issue->level()->isWarning());

        static::assertNotSame($issues, $filtered);
        static::assertCount(1, $filtered);
        static::assertSame([$issue2], [...$filtered]);
    }

    public function test_it_can_detect_the_highest_level(): void
    {
        $issues = new IssueCollection(
            $issue1 = $this->createIssue(Level::warning()),
            $issue2 = $this->createIssue(Level::fatal()),
        );

        static::assertTrue($issues->getHighestLevel()->isFatal());
    }

    public function test_it_can_detect_the_highest_level_on_an_empty_collection(): void
    {
        $issues = new IssueCollection();

        static::assertNull($issues->getHighestLevel());
    }
}
