<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Node;

use XMLReader;

/**
 * @psalm-immutable
 */
final class ElementNode
{
    /**
     * @param list<AttributeNode> $attributes
     */
    public function __construct(
        public int $position,
        public string $name,
        public string $localName,
        public string $namespace,
        public string $namespaceAlias,
        public array $attributes
    ) {
    }

    /**
     * @param callable(): list<AttributeNode>  $attributesProvider
     */
    public static function fromReader(XMLReader $reader, int $position, callable $attributesProvider): self
    {
        return new self(
            $position,
            $reader->name,
            $reader->localName,
            $reader->namespaceURI,
            $reader->prefix,
            $attributesProvider()
        );
    }

    public function position(): int
    {
        return $this->position;
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

    /**
     * @return list<AttributeNode>
     */
    public function attributes(): array
    {
        return $this->attributes;
    }
}
