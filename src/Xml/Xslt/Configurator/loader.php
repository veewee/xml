<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use XSLTProcessor;
use InvalidArgumentException;
use Psl\Result\ResultInterface;
use VeeWee\Xml\Exception\RuntimeException;

use Webmozart\Assert\Assert;
use function Psl\Fun\rethrow;

/**
 * @param callable(XSLTProcessor): ResultInterface<true> $loader
 *
 * @return callable(XSLTProcessor): XSLTProcessor
 */
function loader(callable $loader): callable
{
    return
        /**
         * @throws RuntimeException
         * @throws InvalidArgumentException
         */
        static function (XSLTProcessor $processor) use ($loader): XSLTProcessor {
            $result = $loader($processor);
            Assert::isInstanceOf($result, ResultInterface::class);

            /** @var XSLTProcessor $result */
            $result = $result->proceed(
                static fn (): XSLTProcessor => $processor,
                rethrow()
            );

            return $result;
        };
}
