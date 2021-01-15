<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;
use InvalidArgumentException;
use Psl\Result\ResultInterface;
use VeeWee\Xml\Exception\RuntimeException;

use Webmozart\Assert\Assert;
use function Psl\Fun\rethrow;

/**
 * @param callable(DOMDocument): ResultInterface<true> $loader
 *
 * @return callable(DOMDocument): DOMDocument
 */
function loader(callable $loader): callable
{
    return
        /**
         * @throws RuntimeException
         * @throws InvalidArgumentException
         */
        static function (DOMDocument $document) use ($loader): DOMDocument {
            $result = $loader($document);
            Assert::isInstanceOf($result, ResultInterface::class);

            /** @var DOMDocument $result */
            $result = $result->proceed(
                static fn (): DOMDocument => $document,
                rethrow()
            );

            return $result;
        };
}
