<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMCdataSection;
use DOMDocument;
use DOMNode;
use Webmozart\Assert\Assert;
use function VeeWee\Xml\Dom\Assert\assert_cdata;
use function VeeWee\Xml\Dom\Predicate\is_document;
use function VeeWee\Xml\Internal\configure;

/**
 * @param list<callable(DOMCdataSection): DOMCdataSection> $configurators
 *
 * @return Closure(DOMNode): DOMCdataSection
 */
function cdata(string $data, ...$configurators): Closure
{
    return static function (DOMNode $node) use ($data, $configurators): DOMCdataSection {
        $document = is_document($node) ? $node : $node->ownerDocument;
        Assert::isInstanceOf($document, DOMDocument::class, 'Can not create cdata without a DOM document.');

        return assert_cdata(
            configure(...$configurators)($document->createCDATASection($data))
        );
    };
}
