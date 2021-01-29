<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use DOMDocument;
use DOMNodeList;

/**
 * @return callable(DOMDocument): DOMNodeList
 */
function children(): callable
{
    return static function (DOMDocument $document): DOMNodeList {
        return $document->childNodes;
    };
}
