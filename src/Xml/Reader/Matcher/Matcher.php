<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use VeeWee\Xml\Reader\Node\NodeSequence;

interface Matcher
{
    public function __invoke(NodeSequence $nodeSequence): bool;
}
