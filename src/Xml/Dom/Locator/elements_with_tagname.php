<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use function VeeWee\Xml\Dom\Locator\Element\locate_by_tag_name;

/**
 * @return callable(DOMDocument): DOMNodeList<DOMElement>
 */
function elements_with_tagname(string $tagName): callable
{
    return
        /**
         * @return DOMNodeList<DOMElement>
         */
        static fn(DOMDocument $document): DOMNodeList
            => locate_by_tag_name($document->documentElement, $tagName);
}
