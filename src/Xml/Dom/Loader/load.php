<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use Psl\Result\ResultInterface;

use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @param callable(): bool $loader
 * @return ResultInterface<true>
 */
function load(callable $loader): ResultInterface
{
    return disallow_issues(
        /**
         * @return true
         */
        static function () use ($loader) {
            disallow_libxml_false_returns(
                $loader(),
                'Could not load the DOM Document'
            );

            return true;
        }
    );
}
