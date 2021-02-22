<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Namespaces;

use DOMNameSpaceNode;
use DOMNode;
use DOMNodeList;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Xpath;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

/**
 * @return DOMNodeList<DOMNameSpaceNode>
 */
function recursive_linked_namespaces(DOMNode $node): DOMNodeList
{
    $xpath = Xpath::fromDocument(
        Document::fromUnsafeDocument(
            detect_document($node)
        )
    );

    return $xpath->query('//namespace::*', $node);
}
