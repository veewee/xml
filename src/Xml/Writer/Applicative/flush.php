<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Applicative;

use Closure;
use XMLWriter;

/**
 * @param bool $empty - Whether to empty the buffer or not.
 *
 * @return Closure(XMLWriter): mixed
 */
function flush(bool $empty = true): Closure
{
    return static function (XMLWriter $writer) use ($empty): void {
        $writer->flush($empty);
    };
}
