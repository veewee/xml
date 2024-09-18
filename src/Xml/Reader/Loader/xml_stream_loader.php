<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Loader;

use Closure;
use Webmozart\Assert\Assert;
use XMLReader;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @param resource $stream
 * @return Closure(): XMLReader
 */
function xml_stream_loader(mixed $stream, ?string $encoding = null, int $flags = 0, ?string $documentUri = null): Closure
{
    return static fn (): XMLReader => disallow_issues(
        static function () use ($stream, $encoding, $flags, $documentUri): XMLReader {
            return disallow_libxml_false_returns(
                XMLReader::fromStream($stream, $encoding, $flags, $documentUri),
                'Could not read the provided XML stream!'
            );
        }
    );
}
