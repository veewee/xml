<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Opener;

use Webmozart\Assert\Assert;
use XMLWriter;

/**
 * @return callable(XMLWriter): bool XMLWriter
 */
function xml_file_opener(string $file): callable
{
    return static function (XMLWriter $writer) use ($file) : bool {
        Assert::writable($file);

        return $writer->openUri($file);
    };
}
