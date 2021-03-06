<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xsd;

use DOMDocument;
use Safe\Exceptions\PcreException;
use VeeWee\Xml\Xmlns\Xmlns;
use VeeWee\Xml\Xsd\Schema\Schema;
use VeeWee\Xml\Xsd\Schema\SchemaCollection;
use function Psl\Dict\map;
use function Safe\preg_split;

/**
 * @throws PcreException
 */
function locate_no_namespaced_xsd_schemas(DOMDocument $document): SchemaCollection
{
    $schemaNs = Xmlns::xsd()->value();
    $attributes = $document->documentElement->attributes;
    if (!$schemaLocNoNamespace = $attributes->getNamedItemNS($schemaNs, 'noNamespaceSchemaLocation')) {
        return new SchemaCollection();
    }

    /** @var list<string> $parts */
    $parts = preg_split('/\s+/', trim($schemaLocNoNamespace->textContent));

    return new SchemaCollection(
        ...map(
            $parts,
            static fn (string $location) => Schema::withoutNamespace($location)
        )
    );
}
