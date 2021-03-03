<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Exception;

use Exception;
use VeeWee\Xml\Exception\ExceptionInterface;

final class EncodingException extends Exception implements ExceptionInterface
{
    private function __construct(string $message, Exception $previous = null)
    {
        parent::__construct(
            $message,
            (int) ($previous ? $previous->getCode() : 0),
            $previous
        );
    }

    public static function invalidRoot(string $actualType): self
    {
        return new self('Invalid parent node provided. Expected type array<array|string>, got '.$actualType);
    }

    public static function wrapException(Exception $exception): self
    {
        if ($exception instanceof EncodingException) {
            return $exception;
        }

        return new self($exception->getMessage(), $exception);
    }
}
