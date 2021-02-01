<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use DOMXPath;

/**
 * @template T
 */
interface Locator
{
    /**
     * @return T
     */
    public function __invoke(DOMXPath $xpath): mixed;
}
