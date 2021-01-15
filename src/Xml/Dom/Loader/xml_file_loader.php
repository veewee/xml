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
        Assert::fileExists($file);

        return load(static fn (): bool => $document->load($file));
    };
}
