<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use JsonSerializable;
use Stringable;

/**
 * @psalm-type SupportedValue=scalar|Stringable|JsonSerializable|XmlSerializable
 */
interface XmlSerializable
{
    /**
     * Transform an object into a value for making it XML serializable.
     * The iterable version can contain nested XmlSerializable objects.
     *
     * @return SupportedValue|iterable<SupportedValue>
     */
    public function xmlSerialize(): mixed;
}
