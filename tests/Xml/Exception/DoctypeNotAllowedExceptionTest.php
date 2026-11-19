<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Exception;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use VeeWee\Xml\Exception\DoctypeNotAllowedException;
use VeeWee\Xml\Exception\ExceptionInterface;

final class DoctypeNotAllowedExceptionTest extends TestCase
{
    public function test_it_can_throw_an_exception_explaining_why_doctypes_are_not_allowed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectException(DoctypeNotAllowedException::class);
        $this->expectException(ExceptionInterface::class);
        $this->expectExceptionMessage(
            'The XML document contains a DOCTYPE declaration, which is not allowed. '
            . 'Documents with a DOCTYPE can be used for XML eXternal Entity (XXE) attacks.'
        );
        $this->expectExceptionCode(0);

        throw DoctypeNotAllowedException::create();
    }
}
