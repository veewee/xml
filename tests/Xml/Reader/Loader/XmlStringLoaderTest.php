<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Loader;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Reader\Loader\xml_string_loader;

final class XmlStringLoaderTest extends TestCase
{
    public function test_it_can_handle_invalid_string_loader(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The provided XML can not be empty!');

        xml_string_loader('')();
    }

    public function test_it_can_read_with_encoding(): void
    {
        $reader = xml_string_loader('<?xml version="1.0" encoding="UTF-8"?><hello>héllo</hello>', encoding: 'Windows-1252')();
        $reader->read();
        $actual = $reader->readOuterXml();

        static::assertSame('<hello>hÃ©llo</hello>', $actual);
    }

    public function test_it_can_read_with_libxml_flags(): void
    {
        $reader = xml_string_loader('<?xml version="1.0" encoding="UTF-8"?><hello><![CDATA[hello]]></hello>', flags: LIBXML_NOCDATA)();
        $reader->read();
        $actual = $reader->readOuterXml();

        static::assertSame('<hello>hello</hello>', $actual);
    }
}
