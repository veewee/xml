<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use DOM\XMLDocument as DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\document_uri;
use function VeeWee\Xml\Dom\Validator\xsd_validator;

final class DocumentUriTest extends TestCase
{
    public function test_it_can_use_document_uri(): void
    {
        $doc = Document::fromXmlString('<hello />')->toUnsafeDocument();

        static::assertStringStartsWith(getcwd(), $doc->documentURI);
        $configurator = document_uri($documentUri = 'myfile.wsdl');

        $result = $configurator($doc);
        static::assertSame($doc, $result);
        static::assertSame($documentUri, $doc->documentURI);
    }

    public function test_it_uses_document_uri_in_error_reporting(): void
    {
        $xmlFile = $this->getFixture('xsd-namespace-invalid.xml');
        $xsdFile = $this->getFixture('note-nonamespace.xsd');

        $doc = Document::fromXmlFile(
            $xmlFile,
            document_uri($documentUri = 'myfile.wsdl'),
        );
        $validator = xsd_validator($xsdFile);

        $issues = $doc->validate($validator);

        static::assertCount(1, $issues);
        static::assertSame($documentUri, ([...$issues][0])->file());
    }

    private function getFixture(string $fixture): string
    {
        $file = FIXTURE_DIR.'/dom/validator/xsd/'.$fixture;
        static::assertFileExists($file);

        return $file;
    }
}
