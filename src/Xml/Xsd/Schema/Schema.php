<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xsd\Schema;

/**
 * @psalm-immutable
 */
final class Schema
{
    private function __construct(
        private ?string $namespace,
        private string $location
    ) {
    }

    public static function withoutNamespace(string $location): self
    {
        return new self(null, $location);
    }

    public static function withNamespace(string $namespace, string $location): self
    {
        return new self($namespace, $location);
    }

    public function namespace(): ?string
    {
        return $this->namespace;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function withLocation(string $location): self
    {
        $clone = clone $this;
        $clone->location = $location;

        return $clone;
    }
}
