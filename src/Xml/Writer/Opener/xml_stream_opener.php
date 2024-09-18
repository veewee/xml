<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Opener;

use Closure;
use Psl\File\WriteMode;
use XMLWriter;
use function Psl\File\write;

/**
 * @param resource $stream
 *
 * @return Closure(): XMLWriter
 */
function xml_stream_opener(mixed $stream): Closure
{
    return static function () use ($stream) : XMLWriter {
        return XMLWriter::toStream($stream);
    };
}
