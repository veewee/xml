<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xmlns;

/**
 * @psalm-immutable
 */
final class Xmlns
{
    private string $xmlns;

    private function __construct(string $xmlns)
    {
        $this->xmlns = $xmlns;
    }

    /**
     * @psalm-pure
     */
    public static function xmlns(): self
    {
        return new self('http://www.w3.org/2000/xmlns/');
    }

    /**
     * @psalm-pure
     */
    public static function xml(): self
    {
        return new self('http://www.w3.org/XML/1998/namespace');
    }

    /**
     * @psalm-pure
     */
    public static function xsi(): self
    {
        return new self('http://www.w3.org/2001/XMLSchema-instance');
    }

    /**
     * @psalm-pure
     */
    public static function phpXpath(): self
    {
        return new self('http://php.net/xpath');
    }

    /**
     * @psalm-pure
     */
    public static function load(string $namespace): self
    {
        return new self($namespace);
    }

    public function value(): string
    {
        return $this->xmlns;
    }

    public function matches(Xmlns $other): bool
    {
        return $this->value() === $other->value();
    }
}
