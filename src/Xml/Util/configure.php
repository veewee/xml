<?php declare(strict_types=1);

namespace VeeWee\Xml\Util;

use Closure;

use Psl\Iter;

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
 * @param Closure(T): T ...$stages
 *
 * @return Closure(T): T
 *
 * @pure
 */
function configure(Closure ...$stages): Closure
{
    return static fn ($input) => Iter\reduce(
        $stages,
        /**
         * @param T $input
         * @param (Closure(T): T) $next
         *
         * @return T
         */
        static fn (mixed $input, Closure $next): mixed => $next($input),
        $input
    );
}
