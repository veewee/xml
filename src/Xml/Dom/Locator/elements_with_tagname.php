<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use DOMDocument;

/**
 * @return callable(DOMDocument): DOMNodeList
 */
function elements_with_tagname(string $tagName): callable
{
    return static fn(DOMDocument $document)
        => locate_by_tag_name($document->documentElement, $tagName);
}
