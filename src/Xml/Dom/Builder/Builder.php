<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMNode;

interface Builder
{
    public function __invoke(DOMNode $node): DOMNode;
}
