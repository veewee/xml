<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use DOMDocument;
use Psl\Result\ResultInterface;

interface Loader
{
    /**
     * @return ResultInterface<true>
     */
    public function __invoke(DOMDocument $document): ResultInterface;
}
