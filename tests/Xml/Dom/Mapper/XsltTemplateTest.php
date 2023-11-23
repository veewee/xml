<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Mapper;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Mapper\xslt_template;

final class XsltTemplateTest extends TestCase
{
    public function test_it_can_convert_xml_into_template(): void
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

        static::assertSame('World', $result);
    }

    
    public function test_it_returns_empty_string_on_invalid_value_tag(): void
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

        static::assertSame('', $result);
    }

    
    public function test_it_fails_on_invalid_template_thingies(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to apply the XSLT template');
        
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
