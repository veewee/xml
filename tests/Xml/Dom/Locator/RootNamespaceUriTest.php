<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\root_namespace_uri;

final class RootNamespaceUriTest extends TestCase
{
    public function test_it_can_locate_empty_namespace_uri(): void
    {
        $doc = Document::fromXmlString(<<<EOXML
            <root>
                <item>Hello</item>
                <hello>
                    <item>World</item>
                </hello>
            </root>
        EOXML);
        $uri = $doc->locate(root_namespace_uri());

        static::assertNull($uri);
    }

    public function test_it_can_locate_non_empty_namespace_uri(): void
    {
        $doc = Document::fromXmlString(<<<EOXML
            <root xmlns="http://namespace.com">
                <item>Hello</item>
                <hello>
                    <item>World</item>
                </hello>
            </root>
        EOXML);
        $uri = $doc->locate(root_namespace_uri());

        static::assertSame('http://namespace.com', $uri);
    }
}
