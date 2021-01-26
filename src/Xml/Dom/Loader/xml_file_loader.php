<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use DOMDocument;
use Psl\Result\ResultInterface;
use Webmozart\Assert\Assert;

/**
 * @return callable(DOMDocument): ResultInterface<true>
 */
function xml_file_loader(string $file): callable
{
    return static function (DOMDocument $document) use ($file): ResultInterface {
        return load(
            static function () use ($document, $file): bool {
                Assert::fileExists($file);

                return (bool) $document->load($file);
            }
        );
    };
}
