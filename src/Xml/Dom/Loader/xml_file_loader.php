<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use Closure;
use DOMDocument;
use Webmozart\Assert\Assert;

/**
 * @param int $options - bitmask of LIBXML_* constants https://www.php.net/manual/en/libxml.constants.php
 * @return Closure(DOMDocument): void
 */
function xml_file_loader(string $file, int $options = 0): Closure
{
    return static function (DOMDocument $document) use ($file, $options): void {
        load(
            static function () use ($document, $file, $options): bool {
                Assert::fileExists($file);
                return $document->load($file, $options);
            }
        );
    };
}
