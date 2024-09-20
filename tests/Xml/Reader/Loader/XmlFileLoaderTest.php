<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Loader;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\FillFileTrait;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Reader\Loader\xml_file_loader;

final class XmlFileLoaderTest extends TestCase
{
    use FillFileTrait;

    public function test_it_invalid_file_loader(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The file "invalid-file" does not exist.');

        xml_file_loader('invalid-file')();
    }

    public function test_it_can_read_with_encoding(): void
    {
        [$file, $handle] = $this->fillFile('<?xml version="1.0" encoding="UTF-8"?><hello>héllo</hello>');
        $reader = xml_file_loader($file, encoding: 'Windows-1252')();
        $reader->read();
        $actual = $reader->readOuterXml();

        static::assertSame('<hello>hÃ©llo</hello>', $actual);
    }

    public function test_it_can_read_with_libxml_flags(): void
    {
        [$file, $handle] = $this->fillFile('<?xml version="1.0" encoding="UTF-8"?><hello><![CDATA[hello]]></hello>');
        $reader = xml_file_loader($file, flags: LIBXML_NOCDATA)();
        $reader->read();
        $actual = $reader->readOuterXml();

        static::assertSame('<hello>hello</hello>', $actual);
    }
}
