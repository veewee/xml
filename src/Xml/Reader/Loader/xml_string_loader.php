<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Loader;

use XMLReader;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(): XMLReader
 */
function xml_string_loader(string $xml): callable
{
    return static function () use ($xml): XMLReader {
        return disallow_libxml_false_returns(
            XMLReader::XML($xml),
            'Could not read the provided XML!'
        );
    };
}
