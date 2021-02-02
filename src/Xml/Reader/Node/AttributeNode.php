<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Node;

use XMLReader;

/**
 * @psalm-immutable
 */
final class AttributeNode
{
    public function __construct(
        public string $name,
        public string $localName,
        public string $namespace,
        public string $namespaceAlias,
        public string $value,
    ) {
    }

    public static function fromReader(XMLReader $reader): self
    {
        return new self(
            $reader->name,
            $reader->localName,
            $reader->namespaceURI,
            $reader->prefix,
            $reader->value
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function localName(): string
    {
        return $this->localName;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function namespaceAlias(): string
    {
        return $this->namespaceAlias;
    }

    public function value(): string
    {
        return $this->value;
    }
}
