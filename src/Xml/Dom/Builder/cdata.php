<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \Dom\CDATASection;
use \Dom\Node;
use function VeeWee\Xml\Dom\Assert\assert_cdata;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\Internal\configure;

/**
 * @param list<callable(\Dom\CDATASection): \Dom\CDATASection> $configurators
 *
 * @return Closure(\Dom\Node): \Dom\CDATASection
 */
function cdata(string $data, ...$configurators): Closure
{
    return static function (\Dom\Node $node) use ($data, $configurators): \Dom\CDATASection {
        $document = detect_document($node);

        return assert_cdata(
            configure(...$configurators)($document->createCDATASection($data))
        );
    };
}
