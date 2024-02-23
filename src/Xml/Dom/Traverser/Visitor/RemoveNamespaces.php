<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Visitor;

use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Iter\contains;
use function VeeWee\Xml\Dom\Predicate\is_attribute;
use function VeeWee\Xml\Dom\Predicate\is_element;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;

final class RemoveNamespaces extends AbstractVisitor
{
    /**
     * @var null | callable(\DOM\Attr | \DOM\Element): bool
     */
    private $filter;

    /**
     * @param null | callable(\DOM\Attr): bool $filter
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
            static fn (\DOM\Attr | \DOM\Element $node): bool => $node->prefix !== null
        );
    }

    public static function unprefixed(): self
    {
        return new self(
            static fn (\DOM\Attr | \DOM\Element $node): bool => $node->prefix === null
        );
    }

    /**
     * @param list<string> $prefixes
     */
    public static function byPrefixNames(array $prefixes): self
    {
        return new self(
            static fn (\DOM\Attr | \DOM\Element $node): bool => match(true) {
                is_xmlns_attribute($node) => contains($prefixes, $node->prefix !== null ? $node->localName : ''),
                default => contains($prefixes, $node->prefix ?? '')
            }
        );
    }

    /**
     * @param list<string> $URIs
     */
    public static function byNamespaceURIs(array $URIs): self
    {
        return new self(
            static fn (\DOM\Attr | \DOM\Element $node): bool => match(true) {
                is_xmlns_attribute($node) => contains($URIs, $node->value),
                default => contains($URIs, $node->namespaceURI),
            }
        );
    }

    public function onNodeEnter(\DOM\Node $node): Action
    {
        if (is_xmlns_attribute($node)) {
            return new Action\Noop();
        }

        if (!$this->shouldDealWithNode($node)) {
            return new Action\Noop();
        }

        return new Action\RenameNode($node->localName, null);
    }

    /**
     * @throws RuntimeException
     */
    public function onNodeLeave(\DOM\Node $node): Action
    {
        if (!is_xmlns_attribute($node)) {
            return new Action\Noop();
        }

        if (!$this->shouldDealWithNode($node)) {
            return new Action\Noop();
        }

        return new Action\RemoveNode();
    }

    private function shouldDealWithNode(\DOM\Node $node): bool
    {
        if (!is_element($node) && !is_attribute($node)) {
            return false;
        }

        if ($node->namespaceURI === null) {
            return false;
        }

        if ($this->filter === null) {
            return true;
        }

        return ($this->filter)($node);
    }
}
