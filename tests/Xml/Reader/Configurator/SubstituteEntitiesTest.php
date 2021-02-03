<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Configurator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Reader;
use function VeeWee\Xml\Reader\Configurator\substitute_entities;
use function VeeWee\Xml\Reader\Matcher\node_name;

final class SubstituteEntitiesTest extends TestCase
{
    public function test_it_can_substitute_entities(): void
    {
        $xml = $this->buildXml();
        $reader = Reader::fromXmlString($xml, substitute_entities(true));
        $iterator = $reader->provide(node_name('user'));

        static::assertSame(
            [
                '<user>my entity value</user>',
            ],
            [...$iterator]
        );
    }

    
    public function test_it_can_skip_substituting_entities(): void
    {
        $xml = $this->buildXml();
        $reader = Reader::fromXmlString($xml, substitute_entities(false));
        $iterator = $reader->provide(node_name('user'));

        static::assertSame(
            [
                '<user>&entity;</user>',
            ],
            [...$iterator]
        );
    }

    private function buildXml(): string
    {
        return trim(<<<EOXML
            <?xml version="1.0" standalone="yes" ?>
            <!DOCTYPE user [
                <!ENTITY entity "my entity value">
            ]>
            <root>
                <user>&entity;</user>
            </root>
        EOXML);
    }
}
