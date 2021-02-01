<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Transformer;

use XSLTProcessor;

/**
 * @template T
 */
interface Transformer
{
    /**
     * @param XSLTProcessor $processor
     * @return T
     */
    public function __invoke(XSLTProcessor $processor): mixed;
}
