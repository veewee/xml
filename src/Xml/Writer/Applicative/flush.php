<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Applicative;

use XMLWriter;

/**
 * @param bool $empty - Whether to empty the buffer or not.
 *
 * @return \Closure(XMLWriter): mixed
 */
function flush(bool $empty = true): \Closure {
    return function (XMLWriter $writer) use ($empty): void {
        $writer->flush($empty);
    };
}
