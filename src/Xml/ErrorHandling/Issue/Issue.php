<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling\Issue;

use Psl\Str;

/**
 * @psam-immutable
 */
final class Issue
{
    public function __construct(
        private Level $level,
        private int $code,
        private int $column,
        private string $message,
        private string $file,
        private int $line
    ) {
    }

    public function level(): Level
    {
        return $this->level;
    }

    public function code(): int
    {
        return $this->code;
    }

    public function column(): int
    {
        return $this->column;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function file(): string
    {
        return $this->file;
    }

    public function line(): int
    {
        return $this->line;
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function toString(): string
    {
        return Str\format(
            '[%s] %s: %s (%s) on line %s,%s',
            Str\uppercase($this->level->toString()),
            $this->file,
            $this->message,
            $this->code,
            $this->line,
            $this->column
        );
    }
}
