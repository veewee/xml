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
function xml_string_loader(string $xml): Closure
{
    return static fn (): XMLReader => disallow_issues(
        static function () use ($xml): XMLReader {
            Assert::notEmpty($xml, 'The provided XML can not be empty!');

            return disallow_libxml_false_returns(
                XMLReader::XML($xml),
                'Could not read the provided XML!'
            );
        }
    );
}
