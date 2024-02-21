<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Xslt;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Xslt\Processor;
use function VeeWee\Xml\Xslt\Configurator\functions;

final class FunctionsTest extends TestCase
{
    public function test_it_can_use_php_functions(): void
    {
        $processor = Processor::fromTemplateDocument(
            $this->createTemplate(),
            functions(['strtoupper'])
        );

        $result = $processor->transformDocumentToString(
            Document::fromXmlString(
                <<<EOXML
                    <root>
                        <hello>World</hello>
                    </root>
                EOXML
            )
        );

        static::assertSame('WORLD', $result);
    }

    public function test_it_throws_exception_on_unkown_function(): void
    {
        $processor = Processor::fromTemplateDocument(
            $this->createTemplate(),
            functions(['substr'])
        );
        $doc = Document::fromXmlString(
            <<<EOXML
                <root>
                    <hello>World</hello>
                </root>
            EOXML
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('strtoupper');
        $processor->transformDocumentToString($doc);
    }

    private function createTemplate(): Document
    {
        return Document::fromXmlString(
            <<<EOXML
                <xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                    xmlns:php="http://php.net/xsl"
                    xmlns:str="http://exslt.org/strings"
                    xmlns:xsdl="http://www.w3.org/1999/XSL/Transform">
                    <xsl:output method="text" omit-xml-declaration="yes" indent="no"/>
                    <xsl:template match="/root">
                        <xsl:value-of select="php:function('strtoupper', string(./hello))"/>
                    </xsl:template>
                </xsl:stylesheet>
            EOXML
        );
    }
}
