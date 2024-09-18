<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Dom\Node;

interface Builder
{
    public function __invoke(Node $node): Node;
}
