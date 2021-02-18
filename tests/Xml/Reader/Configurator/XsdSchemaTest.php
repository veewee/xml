<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Configurator;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\FillFileTrait;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Reader\Reader;
use XMLReader;
use function VeeWee\Xml\Reader\Configurator\xsd_schema;
use function VeeWee\Xml\Reader\Matcher\node_name;

final class XsdSchemaTest extends TestCase
{
    use FillFileTrait;

    
    public function test_it_can_iterate_if_the_schema_matches(): void
    {
        [$xsdFile, $xsdHandle] = $this->createXsdFile();
        $xml = <<<EOXML
            <root>
                <user>Jos</user>
                <user>Bos</user>
                <user>Mos</user>    
            </root>
        EOXML;

        $reader = Reader::fromXmlString($xml, xsd_schema($xsdFile));
        $iterator = $reader->provide(node_name('user'));

        static::assertSame(
            [
                '<user>Jos</user>',
                '<user>Bos</user>',
                '<user>Mos</user>'
            ],
            [...$iterator]
        );

        fclose($xsdHandle);
    }

    
    public function test_it_triggers_an_error_on_invalid_schema(): void
    {
        [$xsdFile, $xsdHandle] = $this->createXsdFile();
        $xml = <<<EOXML
            <root>
                <user>Jos</user>
                <user>Bos</user>
                <item>Mos</item>    
            </root>
        EOXML;

        $reader = Reader::fromXmlString($xml, xsd_schema($xsdFile));
        $iterator = $reader->provide(node_name('user'));

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Detected issues during the parsing of the XML Stream');
        [...$iterator];

        fclose($xsdHandle);
    }

    
    public function test_it_triggers_an_error_if_schema_file_does_not_exist(): void
    {
        $xml = '<root />';

        $reader = Reader::fromXmlString($xml, xsd_schema('unkown-file'));
        $iterator = $reader->provide(node_name('user'));

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('The file "unkown-file" does not exist.');
        [...$iterator];

        fclose($xsdHandle);
    }

    
    public function test_it_can_not_set_a_schema_if_the_reader_started_reading(): void
    {
        [$xsdFile, $xsdHandle] = $this->createXsdFile();
        $reader = XMLReader::XML('<root />');
        $reader->read();

        $this->expectException(RuntimeException::class);
        xsd_schema($xsdFile)($reader);

        fclose($xsdHandle);
    }

    
    public function test_it_can_not_set_a_schema_if_the_schema_is_invalid(): void
    {
        [$xsdFile, $xsdHandle] = $this->fillFile('invalid schema');
        $xml = '<root />';

        $reader = Reader::fromXmlString($xml, xsd_schema($xsdFile));
        $iterator = $reader->provide(node_name('user'));

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Schema contains errors');
        [...$iterator];

        fclose($xsdHandle);
    }

    /**
     * @return array{string, resource}
     */
    private function createXsdFile(): array
    {
        return $this->fillFile(trim(
            <<<EOF
                <?xml version="1.0" encoding="UTF-8"?>
                <xs:schema attributeFormDefault="unqualified" elementFormDefault="qualified" xmlns:xs="http://www.w3.org/2001/XMLSchema">
                    <xs:element name="root" type="rootType" />
                    <xs:complexType name="rootType">
                        <xs:sequence>
                            <xs:element type="xs:string" name="user" minOccurs="1" maxOccurs="unbounded" />
                        </xs:sequence>
                    </xs:complexType>
                </xs:schema>
            EOF
        ));
    }
}
