<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\ErrorHandling\Issue\Level;
use VeeWee\Xml\Exception\ExceptionInterface;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Tests\ErrorHandling\Issue\UseIssueTrait;

final class RuntimeExceptionTest extends TestCase
{
    use UseIssueTrait;

    public function test_it_can_throw_an_exception_containing_xml_errors(): void
    {
        $exception = new Exception('nonono');
        $issues = new IssueCollection(
            $this->createIssue(Level::fatal()),
            $this->createIssue(Level::warning()),
        );

        $this->expectException(\RuntimeException::class);
        $this->expectException(RuntimeException::class);
        $this->expectException(ExceptionInterface::class);
        $this->expectExceptionMessage($exception->getMessage() . PHP_EOL . $issues->toString());
        $this->expectExceptionCode($exception->getCode());

        throw RuntimeException::combineExceptionWithIssues($exception, $issues);
    }

    public function test_it_can_throw_an_exception_with_only_xml_errors(): void
    {
        $issues = new IssueCollection(
            $this->createIssue(Level::fatal()),
            $this->createIssue(Level::warning()),
        );

        $this->expectException(\RuntimeException::class);
        $this->expectException(RuntimeException::class);
        $this->expectException(ExceptionInterface::class);
        $this->expectExceptionMessage('got 99 issues' . PHP_EOL . $issues->toString());
        $this->expectExceptionCode(0);

        throw RuntimeException::fromIssues('got 99 issues', $issues);
    }

    public function test_it_can_throw_an_exception_with_only_a_message(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectException(RuntimeException::class);
        $this->expectException(ExceptionInterface::class);
        $this->expectExceptionMessage('got 99 issues');
        $this->expectExceptionCode(0);

        throw RuntimeException::withMessage('got 99 issues');
    }
}
