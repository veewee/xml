<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Opener;

use Closure;
use Psl\File\WriteMode;
use XMLWriter;
use function Psl\File\write;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @param non-empty-string $file
 *
 * @return Closure(): XMLWriter
 */
function xml_file_opener(string $file): Closure
{
    return static fn (): XMLWriter => disallow_issues(static function () use ($file) : XMLWriter {
        // Try to create the file first.
        // If the file exists, it will truncated. (Default behaviour of XMLWriter as well)
        // If it cannot be created, it will throw exceptions.
        write($file, '', WriteMode::Truncate);

        return XMLWriter::toUri($file);
    });
}
