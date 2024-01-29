<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Opener;

use Closure;
use Psl\File\WriteMode;
use XMLWriter;
use function Psl\File\write;

/**
 * @param non-empty-string $file
 *
 * @return Closure(XMLWriter): bool XMLWriter
 */
function xml_file_opener(string $file): Closure
{
    return static function (XMLWriter $writer) use ($file) : bool {
        // Try to create the file first.
        // If the file exists, it will truncated. (Default behaviour of XMLWriter as well)
        // If it cannot be created, it will throw exceptions.
        write($file, '', WriteMode::TRUNCATE);

        return $writer->openUri($file);
    };
}
