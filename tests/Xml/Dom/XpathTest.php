<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom;

use DOMXPath;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Xpath;
use function Psl\Fun\identity;
use function VeeWee\Xml\DOM\xpath\xpath;

final class XpathTest extends TestCase
{
    public function test_it_can_prepare_xpath(): void
    {
        $doc = Document::fromXmlString(
            $xml = '<hello xmlns="http://namespace"><item /></hello>'
        );
        $xpath = Xpath::fromDocument($doc, Xpath\Configurator\namespaces([
            'alias' => 'http://namespace',
        ]));

        $aliasedSearch = $xpath->query('alias:item');
        static::assertCount(1, $aliasedSearch);
    }

    public function test_it_can_prepare_xpath_from_dom_node(): void
    {
        $doc = Document::fromXmlString(
            $xml = '<hello xmlns="http://namespace"><item /></hello>'
        );
        $rootNode = $doc->toUnsafeDocument()->documentElement;

        $xpath = Xpath::fromUnsafeNode($rootNode, Xpath\Configurator\namespaces([
            'alias' => 'http://namespace',
        ]));

        $aliasedSearch = $xpath->query('alias:item');
        static::assertCount(1, $aliasedSearch);
    }

    public function test_it_can_locate_stuff(): void
    {
        $doc = Document::fromXmlString('<root />');
        $xpath = Xpath::fromDocument($doc);

        $result = $xpath->locate(identity());

        static::assertInstanceOf(DOMXPath::class, $result);
    }
}
