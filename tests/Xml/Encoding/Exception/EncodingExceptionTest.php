<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Encoding\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use VeeWee\Xml\Exception\ExceptionInterface;

final class EncodingExceptionTest extends TestCase
{
    public function test_invalid_root_node(): void
    {
        $this->expectException(Exception::class);
        $this->expectException(EncodingException::class);
        $this->expectException(ExceptionInterface::class);
        $this->expectExceptionMessage('Invalid parent node provided. Expected type array<array|string>, got int');
        $this->expectExceptionCode(0);

        throw EncodingException::invalidRoot('int');
    }

    public function test_it_can_wrap_any_exception(): void
    {
        $exception = new Exception('hello there', 1);
        $actual = EncodingException::wrapException($exception);

        static::assertNotSame($exception, $actual);

        $this->expectException(EncodingException::class);
        $this->expectExceptionMessage($exception->getMessage());
        $this->expectExceptionCode(1);

        throw $actual;
    }

    public function test_it_does_not_wrap_an_encoding_exception(): void
    {
        $exception = EncodingException::invalidRoot('mixed');
        $actual = EncodingException::wrapException($exception);

        static::assertSame($exception, $actual);
    }
}
