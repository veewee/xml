<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Xslt;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;
use function VeeWee\Xml\Xslt\Configurator\security_preferences;

final class SecurityPreferencesTest extends TestCase
{
    public function test_it_can_set_security_preferences(): void
    {
        $processor = Processor::fromTemplateDocument(
            $this->createTemplate(),
            security_preferences(XSL_SECPREF_NONE)
        );

        $result = $processor->transformDocumentToString(
            Document::fromXmlString('<root>Hello</root>')
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
