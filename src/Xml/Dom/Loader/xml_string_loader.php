<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use DOMDocument;
use Psl\Result\ResultInterface;

/**
 * @return callable(DOMDocument): ResultInterface<true>
 */
function xml_string_loader(string $xml): callable
{
    return static function (DOMDocument $document) use ($xml): ResultInterface {
        return load(static fn () => (bool) $document->loadXML($xml));
    };
}
