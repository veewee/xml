<?php

declare(strict_types=1);

namespace VeeWee\Xml\Exception;

use RuntimeException;

final class DoctypeNotAllowedException extends RuntimeException implements ExceptionInterface
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function create(): self
    {
        return new self(
            'The XML document contains a DOCTYPE declaration, which is not allowed. '
            . 'Documents with a DOCTYPE can be used for XML eXternal Entity (XXE) attacks.'
        );
    }
}
