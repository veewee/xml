<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xsd;

use DOMDocument;
use Safe\Exceptions\PcreException;
use VeeWee\Xml\Xsd\SchemaCollection;

/**
 * @throws PcreException
 */
function locate_all_xsd_schemas(DOMDocument $document): SchemaCollection
{
    return new SchemaCollection(
        ...iterator_to_array(locate_namespaced_xsd_schemas($document)),
        ...iterator_to_array(locate_no_namespaced_xsd_schemas($document))
    );
}
