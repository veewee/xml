<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use \DOM\XPath;

/**
 * @template T
 */
interface Locator
{
    /**
     * @return T
     */
    public function __invoke(\DOM\XPath $xpath): mixed;
}
