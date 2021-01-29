<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Loader;

use Webmozart\Assert\Assert;
use XMLReader;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(): XMLReader
 */
function xml_file_loader(string $file): callable
{
    return static fn () => disallow_issues(
        static function () use ($file) {
            Assert::fileExists($file);
            return disallow_libxml_false_returns(
                XMLReader::open($file),
                'Could not open the provided XML file!'
            );
        }
    );
}
