<?php

declare(strict_types=1);

namespace VeeWee\Xml\Exception;

use Throwable;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;

final class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    private function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct(
            $message,
            (int) ($previous ? $previous->getCode() : 0),
            $previous
        );
    }

    public static function withMessage(string $message): self
    {
        return new self($message);
    }

    public static function fromIssues(string $message, IssueCollection $errors): self
    {
        return new self($message . PHP_EOL . $errors->toString());
    }

    public static function combineExceptionWithIssues(Throwable $exception, IssueCollection $errors): self
    {
        return new self(
            $exception->getMessage() . PHP_EOL . $errors->toString(),
            $exception
        );
    }
}
