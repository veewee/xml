<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xsd;

use \Dom\XMLDocument;
use Psl\Regex\Exception\RuntimeException;
use VeeWee\Xml\Xmlns\Xmlns;
use VeeWee\Xml\Xsd\Schema\Schema;
use VeeWee\Xml\Xsd\Schema\SchemaCollection;
use function Psl\Dict\map;
use function Psl\Regex\split;
use function VeeWee\Xml\Dom\Locator\document_element;

/**
 * @throws RuntimeException
 */
function locate_no_namespaced_xsd_schemas(\Dom\XMLDocument $document): SchemaCollection
{
    $schemaNs = Xmlns::xsi()->value();
    $documentElement = document_element()($document);
    $attributes = $documentElement->attributes;
    if (!$schemaLocNoNamespace = $attributes->getNamedItemNS($schemaNs, 'noNamespaceSchemaLocation')) {
        return new SchemaCollection();
    }

    $parts = split(trim($schemaLocNoNamespace->textContent ?? ''), '/\s+/');

    return new SchemaCollection(
        ...map(
            $parts,
            static fn (string $location) => Schema::withoutNamespace($location)
        )
    );
}
