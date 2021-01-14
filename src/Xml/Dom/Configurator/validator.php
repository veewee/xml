<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;
use Psl\Result\ResultInterface;
use VeeWee\Xml\Exception\RuntimeException;
use Webmozart\Assert\Assert;
use function Psl\Fun\rethrow;

/**
 * @param callable(DOMDocument): ResultInterface<bool> $validator
 *
 * @return callable(DOMDocument): DOMDocument
 */
function validator(callable $validator): callable
{
    return
        /**
         * @throws RuntimeException
         */
        static function (DOMDocument $document) use ($validator): DOMDocument {
            $result = $validator($document);
            Assert::isInstanceOf($result, ResultInterface::class);

            return $result->proceed(
                static fn () => $document,
                rethrow()
            );
        };
}
