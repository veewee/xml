<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use Dom\XMLDocument;

/**
 * @template R
 */
interface Mapper
{
    /**
     * @return R
     */
    public function __invoke(XMLDocument $document): mixed;
}
