<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Loader;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\FillFileTrait;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Reader\Loader\xml_stream_loader;

final class XmlStreamLoaderTest extends TestCase
{
    use FillFileTrait;

    public function test_it_can_handle_invalid_stream_loader(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('supplied resource is not a valid stream resource');

        [$_, $handle]  = $this->fillFile('xxx');
        fclose($handle);

        xml_stream_loader($handle)();
    }

    public function test_it_can_read_with_encoding(): void
    {
        [$file, $handle] = $this->fillFile('<?xml version="1.0" encoding="UTF-8"?><hello>héllo</hello>');
        rewind($handle);

        $reader = xml_stream_loader($handle, encoding: 'Windows-1252')();
        $reader->read();
        $actual = $reader->readOuterXml();

        static::assertSame('<hello>hÃ©llo</hello>', $actual);
    }

    public function test_it_can_read_with_libxml_flags(): void
    {
        [$file, $handle] = $this->fillFile('<?xml version="1.0" encoding="UTF-8"?><hello><![CDATA[hello]]></hello>');
        rewind($handle);

        $reader = xml_stream_loader($handle, flags: LIBXML_NOCDATA)();
        $reader->read();
        $actual = $reader->readOuterXml();

        static::assertSame('<hello>hello</hello>', $actual);
    }

    public function test_it_can_set_a_base_uri(): void
    {
        [$file, $handle] = $this->fillFile('<hello>hello</hello>');
        rewind($handle);

        $reader = xml_stream_loader($handle, documentUri: $documentUri = 'http://xxxx')();
        $reader->read();
        $actual = $reader->readOuterXml();

        static::assertSame($documentUri, $reader->baseURI);
        static::assertSame('<hello>hello</hello>', $actual);
    }
}
