<?php

namespace VeeWee\Xml\Writer\Applicative;

interface Applicative
{
    /**
     * @return mixed
     */
    public function __invoke(\XMLWriter $writer): mixed;
}
