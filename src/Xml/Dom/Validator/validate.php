<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use Psl\Result\ResultInterface;
use VeeWee\Xml\Exception\RuntimeException;
use Webmozart\Assert\Assert;
use function Psl\Fun\rethrow;
use function VeeWee\Xml\ErrorHandling\detect_errors;

/**
 * @param callable(): bool $validators
 *
 * @throws RuntimeException
 *
 * @return ResultInterface<true>
 */
function validate(callable $validator): ResultInterface
{
    [$result, $issues] = detect_errors($validator);
    Assert::isInstanceOf($result, ResultInterface::class);

    return $result->then(
        static function (bool $result) use ($issues): bool  {
            if (!$result) {
                throw RuntimeException::fromIssues('Invalid XML', $issues);
            }

            return true;
        },
        rethrow()
    );
}
