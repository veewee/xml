<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Loader;

use Closure;
use XMLReader;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @param resource $stream
 * @return Closure(): XMLReader
 */
function xml_stream_loader(mixed $stream, ?string $encoding = null, int $flags = 0, ?string $documentUri = null): Closure
{
    return static fn (): XMLReader => disallow_issues(
        static fn (): XMLReader => XMLReader::fromStream($stream, $encoding, $flags, $documentUri)
    );
}
