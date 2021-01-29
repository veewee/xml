<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use DOMDocument;

/**
 * @param string $tagName
 *
 * @return callable(DOMDocument): DOMNodeList
 */
function elements_with_namespaced_tagname(string $namespace, string $localTagName): callable
{
    return static fn(DOMDocument $document)
        => locate_by_namespaced_tag_name($document->documentElement, $namespace, $localTagName);
}
