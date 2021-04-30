<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Visitor;

use DOMElement;
use DOMNameSpaceNode;
use DOMNode;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Xmlns\Xmlns;
use function VeeWee\Xml\Dom\Predicate\is_default_xmlns_attribute;
use function VeeWee\Xml\Dom\Predicate\is_document_element;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;

class OptimizeNamespaces extends AbstractVisitor
{
    private string $prefix;

    /**
     * @var array<string, string> URI - new prefix
     */
    private array $knownNamespaces = [];

    public function __construct(
        string $prefix = 'ns'
    ) {
        $this->prefix = $prefix;
    }

    public function onNodeLeave(DOMNode $node): Action
    {
        return match(true) {
            is_xmlns_attribute($node) => $this->rememberAndRemoveXmlnsAttribute($node),
            is_document_element($node) => $this->registerNewNamespacesOnRoot($node),
            default => new Action\Noop()
        };
    }

    private function rememberAndRemoveXmlnsAttribute(DOMNameSpaceNode $node): Action
    {
        if (is_default_xmlns_attribute($node)) {
            return new Action\Noop();
        }

        $this->rememberNamespace($node->namespaceURI);

        return new Action\RemoveNode();
    }

    private function registerNewNamespacesOnRoot(DOMElement $node): Action
    {
        foreach ($this->knownNamespaces as $uri => $newPrefix) {
            $node->setAttributeNS(
                Xmlns::xmlns()->value(),
                'xmlns:'.$newPrefix,
                $uri
            );
        }

        return new Action\Noop();
    }

    private function rememberNamespace(string $namespaceUri): void
    {
        if (array_key_exists($namespaceUri, $this->knownNamespaces)) {
            return;
        }

        $this->knownNamespaces[$namespaceUri] = $this->prefix . (count($this->knownNamespaces) + 1);
    }
}
