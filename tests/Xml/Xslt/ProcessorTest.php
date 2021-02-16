<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Xslt;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Mapper\from_template_document;
use function VeeWee\Xml\Xslt\Configurator\loader;
use function VeeWee\Xml\Xslt\Transformer\document_to_string;

final class ProcessorTest extends TestCase
{
    public function test_it_can_use_processor_directly(): void
    {
        $template = $this->createTemplate();
        $processor = Processor::configure(
            loader(from_template_document($template)),
            identity()
        );

        $document = Document::fromXmlString('<root>Hello</root>');
        $result = $processor->transform(
            document_to_string($document)
        );

        static::assertSame('Hello', $result);
    }

    private function createTemplate(): Document
    {
        return Document::fromXmlString(
            <<<EOXML
                <xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                    xmlns:str="http://exslt.org/strings"
                    xmlns:xsdl="http://www.w3.org/1999/XSL/Transform">
                    <xsl:output method="text" omit-xml-declaration="yes" indent="no"/>
                    <xsl:template match="/root">
                        <xsl:value-of select="."/>
                    </xsl:template>
                </xsl:stylesheet>
            EOXML
        );
    }
}
