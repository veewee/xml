<?php
declare(strict_types=1);

namespace VeeWee\Xml\Reader;

use DOMDocument;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Encoding\xml_decode;

final class MatchingNode
{
    /**
     * @param non-empty-string $xml
     */
    public function __construct(
        private readonly string $xml,
        private readonly NodeSequence $nodeSequence
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function xml(): string
    {
        return $this->xml;
    }

    public function nodeSequence(): NodeSequence
    {
        return $this->nodeSequence;
    }

    /**
     * @param list<callable(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public function intoDocument(callable ... $configurators): Document
    {
        return Document::fromXmlString($this->xml, ...$configurators);
    }

    /**
     * @param list<callable(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     * @throws EncodingException
     */
    public function decode(callable ... $configurators): array
    {
        return xml_decode($this->xml, ...$configurators);
    }

    /**
     * @param callable(NodeSequence): bool $matcher
     */
    public function matches(callable $matcher): bool
    {
        return $matcher($this->nodeSequence);
    }
}
