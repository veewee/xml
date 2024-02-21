<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use \DOM\XMLDocument;

/**
 * @template R
 */
interface Mapper
{
    /**
     * @return R
     */
    public function __invoke(\DOM\XMLDocument $document): mixed;
}
