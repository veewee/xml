<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Xpath\Configurator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Xpath;

final class NamespacesTest extends TestCase
{
    public function test_it_can_fetch_namespaced(): void
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
}
