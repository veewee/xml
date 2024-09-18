<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use Closure;
use Dom\Element;
use Dom\XMLDocument;
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\Dom\Locator\Element\locate_by_tag_name;

/**
 * @return Closure(XMLDocument): NodeList<Element>
 */
function elements_with_tagname(string $tagName): Closure
{
    return
        /**
         * @return NodeList<Element>
         */
        static fn (XMLDocument $document): NodeList
            => locate_by_tag_name(
                document_element()($document),
                $tagName
            );
}
