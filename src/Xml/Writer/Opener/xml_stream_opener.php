<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Opener;

use Closure;
use XMLWriter;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @param resource $stream
 *
 * @return Closure(): XMLWriter
 */
function xml_stream_opener(mixed $stream): Closure
{
    return static fn (): XMLWriter => disallow_issues(static function () use ($stream) : XMLWriter {
        return XMLWriter::toStream($stream);
    });
}
