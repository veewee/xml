<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Configurator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Reader\Reader;
use XMLReader;
use function VeeWee\Xml\Reader\Configurator\parser_options;
use function VeeWee\Xml\Reader\Matcher\node_name;

final class ParserOptionsTest extends TestCase
{
    public function test_it_throws_when_you_provide_an_invalid_option(): void
    {
        $xml = '<root />';
        $reader = Reader::fromXmlString($xml, parser_options([9019203 => true]));
        $iterator = $reader->provide(node_name('user'));

        $this->expectException(RuntimeException::class);

        [...$iterator];
    }

    
    public function test_it_does_not_throw_exceptions_when_called_after_read_has_been_called(): void
    {
        $xml = '<root />';
        $reader = XMLReader::XML($xml);
        $reader->read();

        $result = parser_options([XMLReader::VALIDATE => false])($reader);

        static::assertSame($result, $reader);
    }
}
