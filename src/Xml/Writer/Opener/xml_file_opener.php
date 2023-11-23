<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Opener;

use Closure;
use Webmozart\Assert\Assert;
use XMLWriter;

/**
 * @return Closure(XMLWriter): bool XMLWriter
 */
function xml_file_opener(string $file): Closure
{
    return static function (XMLWriter $writer) use ($file) : bool {
        Assert::writable($file);

        return $writer->openUri($file);
    };
}
