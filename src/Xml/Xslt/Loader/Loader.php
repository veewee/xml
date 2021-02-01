<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Loader;

use Psl\Result\ResultInterface;
use XSLTProcessor;

interface Loader
{
    /**
     * @return ResultInterface<true>
     */
    public function __invoke(XSLTProcessor $processor): ResultInterface;
}
