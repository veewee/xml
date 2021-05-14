<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use DOMDocument;
use Webmozart\Assert\Assert;

/**
 * @param int $options - bitmask of LIBXML_* constants https://www.php.net/manual/en/libxml.constants.php
 * @return callable(DOMDocument): void
 */
function xml_file_loader(string $file, int $options = 0): callable
{
    return static function (DOMDocument $document) use ($file, $options): void {
        load(
            static function () use ($document, $file, $options): bool {
                Assert::fileExists($file);
                return (bool) $document->load($file, $options);
            }
        );
    };
}
