<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Loader;

use Closure;
use Webmozart\Assert\Assert;
use XMLReader;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @return Closure(): XMLReader
 */
function xml_file_loader(string $file, ?string $encoding = null, int $flags = 0): Closure
{
    return static fn (): XMLReader => disallow_issues(
        static function () use ($file, $encoding, $flags): XMLReader {
            Assert::fileExists($file);

            return XMLReader::fromUri($file, $encoding, $flags);
        }
    );
}
