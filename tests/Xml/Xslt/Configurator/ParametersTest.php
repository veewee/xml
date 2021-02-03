<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Xslt;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Xslt\Processor;
use function VeeWee\Xml\Xslt\Configurator\parameters;

class ParametersTest extends TestCase
{
    public function test_it_can_use_php_functions(): void
    {
        $processor = Processor::fromTemplateDocument(
            $this->createTemplate(),
            parameters('', ['hello' => 'world'])
        );

        $result = $processor->transformDocumentToString(
            Document::fromXmlString('<root />')
        );

        static::assertSame('world', $result);
    }

    public function test_it_throws_exception_if_param_is_not_set(): void
    {
        $processor = Processor::fromTemplateDocument(
            $this->createTemplate(),
        );
        $doc = Document::fromXmlString(
            <<<EOXML
                <root>
                    <hello>World</hello>
                </root>
            EOXML
        );

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('XML issues detecte');
        $processor->transformDocumentToString($doc);
    }

    /** @test */
    public function it_can_set_namespaced_param(): void
    {
        throw new \Exception('TODO');
        $processor = Processor::fromTemplateDocument(
            $this->createTemplate(),
            parameters('namespaced', ['hello' => 'world'])
        );

        $result = $processor->transformDocumentToString(
            Document::fromXmlString('<root />')
        );

        static::assertSame('world', $result);

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
                        <xsl:value-of select="\$hello"/>
                    </xsl:template>
                </xsl:stylesheet>
            EOXML
        );
    }
}
