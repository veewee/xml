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
}
