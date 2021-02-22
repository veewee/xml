<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xsd;

use DOMDocument;
use Safe\Exceptions\PcreException;
use VeeWee\Xml\Xmlns\Xmlns;
use VeeWee\Xml\Xsd\Schema\Schema;
use VeeWee\Xml\Xsd\Schema\SchemaCollection;
use function Safe\preg_split;

/**
 * @throws PcreException
 */
function locate_namespaced_xsd_schemas(DOMDocument $document): SchemaCollection
{
    $schemaNs = Xmlns::xsd()->value();
    $attributes = $document->documentElement->attributes;
    $collection = new SchemaCollection();

    if (!$schemaLocation = $attributes->getNamedItemNS($schemaNs, 'schemaLocation')) {
        return $collection;
    }

    /** @var list<string> $parts */
    $parts = preg_split('/\s+/', trim($schemaLocation->textContent));
    $partsCount = count($parts);
    for ($k = 0; $k < $partsCount; $k += 2) {
        $collection = $collection->add(Schema::withNamespace($parts[$k], $parts[$k + 1]));
    }

    return $collection;
}
