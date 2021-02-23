<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder;

use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xmlns\Xmlns;
use function VeeWee\Xml\Encoding\Internal\Decoder\Builder\known_namespace_prefixes;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
final class Context
{
    private function __construct(
        private Document $document,
        /**
         * @var array<string, Xmlns>
         */
        private array $knownNamespaces
    ) {
    }

    public static function fromDocument(Document $document)
    {
        return new self(
            $document,
            known_namespace_prefixes($document->toUnsafeDocument())
        );
    }

    public function document(): Document
    {
        return $this->document;
    }

    /**
     * @return array<string, Xmlns>
     */
    public function knownNamespaces(): array
    {
        return $this->knownNamespaces;
    }
}
