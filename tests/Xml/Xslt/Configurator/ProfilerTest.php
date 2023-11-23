<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Xslt;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\TmpFileTrait;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Xslt\Processor;
use function VeeWee\Xml\Xslt\Configurator\profiler;

final class ProfilerTest extends TestCase
{
    use TmpFileTrait;

    public function test_it_can_use_a_profiler(): void
    {
        $this->createTmpFile(function ($profile) {
            $processor = Processor::fromTemplateDocument(
                $this->createTemplate(),
                profiler($profile)
            );

            $processor->transformDocumentToString(
                Document::fromXmlString('<root>Hello</root>')
            );

            static::assertFileExists($profile);
            static::assertNotSame('', file_get_contents($profile));
        });
    }

    public function test_setting_an_invalid_profiler_location_doesnt_result(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The file "/THISFOLDERSHOULDNOTEXIST" does not exist.');

        Processor::fromTemplateDocument(
            $this->createTemplate(),
            profiler('/THISFOLDERSHOULDNOTEXIST/profile.txt')
        );
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
