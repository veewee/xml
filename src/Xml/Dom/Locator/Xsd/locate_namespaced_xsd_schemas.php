<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xsd;

use DOMDocument;
use Psl\Regex\Exception\RuntimeException;
use VeeWee\Xml\Xmlns\Xmlns;
use VeeWee\Xml\Xsd\Schema\Schema;
use VeeWee\Xml\Xsd\Schema\SchemaCollection;
use function Psl\Regex\split;

/**
 * @throws RuntimeException
 */
function locate_namespaced_xsd_schemas(DOMDocument $document): SchemaCollection
{
    $schemaNs = Xmlns::xsi()->value();
    $attributes = $document->documentElement->attributes;
    $collection = new SchemaCollection();

    if (!$schemaLocation = $attributes->getNamedItemNS($schemaNs, 'schemaLocation')) {
        return $collection;
    }

    /** @psalm-suppress MissingThrowsDocblock - Covered the runtime exception! */
    $parts = split(trim($schemaLocation->textContent), '/\s+/');
    $partsCount = count($parts);
    for ($k = 0; $k < $partsCount; $k += 2) {
        $collection = $collection->add(Schema::withNamespace($parts[$k], $parts[$k + 1]));
    }

    return $collection;
}
