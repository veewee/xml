<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use Closure;
use Dom\XMLDocument;
use Webmozart\Assert\Assert;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @param int $options - bitmask of LIBXML_* constants https://www.php.net/manual/en/libxml.constants.php
 * @return Closure(): XMLDocument
 */
function xml_file_loader(string $file, int $options = 0, ?string $override_encoding = null): Closure
{
    return static fn () => disallow_issues(static function () use ($file, $options, $override_encoding): XMLDocument {
        Assert::fileExists($file);

        return XMLDocument::createFromFile($file, $options, $override_encoding);
    });
}
