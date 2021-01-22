<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xsd;

use DOMDocument;
use Safe\Exceptions\PcreException;

/**
 * @return iterable<int, string>
 * @throws PcreException
 */
function locate_all_xsd_schemas(DOMDocument $document): iterable
{
    yield from locate_namespaced_xsd_schemas($document);
    yield from locate_no_namespaced_xsd_schemas($document);
}
