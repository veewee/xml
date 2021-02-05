<?php

declare(strict_types=1);

namespace VeeWee\Xml\Exception;

use Exception;
use Psl\Type;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;

final class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    private function __construct(string $message, Exception $previous = null)
    {
        try {
            $code = Type\int()->coerce($previous ? $previous->getCode() : 0);
        } catch (Type\Exception\CoercionException $exception) {
            $code = 0;
        }

        parent::__construct($message, $code, $previous);
    }

    public static function withMessage(string $message): self
    {
        return new self($message);
    }

    public static function fromIssues(string $message, IssueCollection $errors): self
    {
        return new self($message . PHP_EOL . $errors->toString());
    }

    public static function combineExceptionWithIssues(Exception $exception, IssueCollection $errors): self
    {
        return new self(
            $exception->getMessage() . PHP_EOL . $errors->toString(),
            $exception
        );
    }
}
