<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use \DOM\Node;

interface Builder
{
    public function __invoke(\DOM\Node $node): \DOM\Node;
}
