<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Visitor;

use DOMNameSpaceNode;
use DOMNode;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Iter\contains;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;
use function VeeWee\Xml\Dom\Predicate\is_element;

final class RemoveNamespaces extends AbstractVisitor
{
    /**
     * @var null | callable(DOMNameSpaceNode): bool
     */
    private $filter;

    /**
     * @param null | callable(DOMNameSpaceNode): bool $filter
     */
    public function __construct(
        ?callable $filter = null
    ) {
        $this->filter = $filter;
    }

    public static function all(): self
    {
        return new self();
    }

    public static function prefixed(): self
    {
        return new self(
            static fn (DOMNameSpaceNode $node): bool => $node->prefix !== ''
        );
    }

    public static function unprefixed(): self
    {
        return new self(
            static fn (DOMNameSpaceNode $node): bool => $node->prefix === ''
        );
    }

    /**
     * @param list<string> $prefixes
     */
    public static function byPrefixNames(array $prefixes): self
    {
        return new self(
            static fn (DOMNameSpaceNode $node): bool => contains($prefixes, $node->prefix)
        );
    }

    /**
     * @param list<string> $URIs
     */
    public static function byNamespaceURIs(array $URIs): self
    {
        return new self(
            static fn (DOMNameSpaceNode $node): bool => contains($URIs, $node->namespaceURI)
        );
    }

    /**
     * @throws RuntimeException
     */
    public function onNodeLeave(DOMNode $node): Action
    {
        if (!is_element($node)) {
            return new Action\Noop();
        }

        $namespaces = xmlns_attributes_list($node);
        if ($this->filter !== null) {
            $namespaces = $namespaces->filter($this->filter);
        }

        foreach ($namespaces as $namespace) {
            $node->removeAttributeNS(
                $namespace->namespaceURI,
                $namespace->prefix
            );
        }

        return new Action\Noop();
    }
}
