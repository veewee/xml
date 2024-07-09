<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use \Dom\XPath;

/**
 * @template T
 */
interface Locator
{
    /**
     * @return T
     */
    public function __invoke(\Dom\XPath $xpath): mixed;
}
