<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Builder\default_xmlns_attribute;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\namespaced_element;

final class DefaultXmlnsAttributeTest extends TestCase
{
    public function test_it_can_build_an_element_with_default_xmlns_on_namespaced_element(): void
    {
        $doc = Document::empty()->toUnsafeDocument();

        $node = namespaced_element(
            'uri://x',
            'x:foo',
            default_xmlns_attribute('uri://default')
        )($doc);

        static::assertSame('<x:foo xmlns:x="uri://x" xmlns="uri://default"/>', $doc->saveXml($node));
    }

    public function test_it_can_not_build_an_element_with_default_xmlns_on_regular_element(): void
    {
        $doc = Document::empty()->toUnsafeDocument();

        $node = element(
            'foo',
            default_xmlns_attribute('uri://default')
        )($doc);

        static::assertSame('<foo/>', $doc->saveXml($node));
    }
}
