<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMCdataSection;
use DOMNode;
use function VeeWee\Xml\Dom\Assert\assert_cdata;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\Internal\configure;

/**
 * @param list<callable(DOMCdataSection): DOMCdataSection> $configurators
 *
 * @return Closure(DOMNode): DOMCdataSection
 */
function cdata(string $data, ...$configurators): Closure
{
    return static function (DOMNode $node) use ($data, $configurators): DOMCdataSection {
        $document = detect_document($node);

        return assert_cdata(
            configure(...$configurators)($document->createCDATASection($data))
        );
    };
}
