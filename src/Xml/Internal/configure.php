<?php declare(strict_types=1);

namespace VeeWee\Xml\Internal;

use Closure;

/**
 * @psalm-internal \Veewee\Xml
 *
 * Performs left-to-right function composition.
 * A copy os Psl\Fun\Pipe that expects the type to be the same during all pipes.
 * (The current psalm pipe plugin doesn't work well with generic closures)
 * @see https://github.com/vimeo/psalm/issues/7244
 *
 * @template T
 *
 * @param callable(T): T ...$stages
 *
 * @return Closure(T): T
 *
 * @pure
 */
function configure(callable ...$stages): Closure
{
    return
        /**
         * @param T $input
         * @return T
         */
        static function (mixed $input) use ($stages): mixed {
            foreach ($stages as $stage) {
                $input = $stage($input);
            }

            return $input;
        };
}
