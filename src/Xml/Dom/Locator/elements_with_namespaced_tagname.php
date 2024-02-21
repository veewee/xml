<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use Closure;
use \DOM\XMLDocument;
use \DOM\Element;
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\Dom\Locator\Element\locate_by_namespaced_tag_name;

/**
 * @return Closure(\DOM\XMLDocument): NodeList<\DOM\Element>
 */
function elements_with_namespaced_tagname(string $namespace, string $localTagName): Closure
{
    return
        /**
         * @return NodeList<\DOM\Element>
         */
        static fn (\DOM\XMLDocument $document): NodeList
            => locate_by_namespaced_tag_name($document->documentElement, $namespace, $localTagName);
}
