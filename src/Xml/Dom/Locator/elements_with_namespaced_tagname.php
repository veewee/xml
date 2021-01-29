<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use function VeeWee\Xml\Dom\Locator\Element\locate_by_namespaced_tag_name;

/**
 * @param string $tagName
 *
 * @return callable(DOMDocument): DOMNodeList
 */
function elements_with_namespaced_tagname(string $namespace, string $localTagName): callable
{
    return
        /**
         * @return DOMNodeList<DOMElement>
         */
        static fn(DOMDocument $document): DOMNodeList
            => locate_by_namespaced_tag_name($document->documentElement, $namespace, $localTagName);
}
