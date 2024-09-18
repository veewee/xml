<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Loader;

use Closure;
use Webmozart\Assert\Assert;
use XMLReader;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return Closure(): XMLReader
 */
function xml_string_loader(string $xml, ?string $encoding = null, int $flags = 0): Closure
{
    return static fn (): XMLReader => disallow_issues(
        static function () use ($xml, $encoding, $flags): XMLReader {
            Assert::notEmpty($xml, 'The provided XML can not be empty!');

            return disallow_libxml_false_returns(
                XMLReader::fromString($xml, $encoding, $flags),
                'Could not read the provided XML!'
            );
        }
    );
}
