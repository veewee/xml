<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Mapper;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Mapper\xslt_template;

class XsltTemplateTest extends TestCase
{
    /** @test */
    public function it_can_convert_xml_into_template(): void
    {
        $result = xslt_template($this->createTemplate())(
            Document::fromXmlString(
                <<<EOXML
                    <root>
                        <hello>World</hello>
                    </root>
                EOXML
            )->toUnsafeDocument()
        );

        self::assertSame('World', $result);
    }

    /** @test */
    public function it_returns_empty_string_on_invalid_value_tag(): void
    {
        $result = xslt_template($this->createTemplate())(
            Document::fromXmlString(
                <<<EOXML
                    <root>
                        <hellodoesnotexist />
                    </root>
                EOXML
            )->toUnsafeDocument()
        );

        self::assertSame('', $result);
    }

    /** @test */
    public function it_fails_on_invalid_template_thingies(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('XML issues detected');
        
        xslt_template(
            Document::fromXmlString(
                <<<EOXML
                    <xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:str="http://exslt.org/strings" xmlns:xsdl="http://www.w3.org/1999/XSL/Transform">
                        <xsl:output method="text" omit-xml-declaration="yes" indent="no"/>
                        <xsl:template match="/root">
                            <xsl:value-of select="unkownfunction(./hello)"/>
                        </xsl:template>
                    </xsl:stylesheet>
                EOXML
            )
        )(
            Document::fromXmlString(
                <<<EOXML
                    <root>
                        <hello>World</hello>
                    </root>
                EOXML
            )->toUnsafeDocument()
        );
    }

    private function createTemplate(): Document
    {
        return Document::fromXmlString(
            <<<EOXML
                <xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:str="http://exslt.org/strings" xmlns:xsdl="http://www.w3.org/1999/XSL/Transform">
                    <xsl:output method="text" omit-xml-declaration="yes" indent="no"/>
                    <xsl:template match="/root">
                        <xsl:value-of select="./hello"/>
                    </xsl:template>
                </xsl:stylesheet>
            EOXML
        );
    }
}
