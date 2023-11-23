<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Opener;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\TmpFileTrait;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Writer\Writer;
use function VeeWee\Xml\Writer\Builder\element;

final class XmlFileOpenerTest extends TestCase
{
    use TmpFileTrait;

    
    public function test_it_can_open_a_file(): void
    {
        $this->createTmpFile(static function (string $path): void {
            $writer = Writer::forFile($path);
            $writer->write(element('root'));

            self::assertXmlStringEqualsXmlFile($path, '<root />');
        });
    }

    
    public function test_it_errors_if_file_is_not_writable(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The path "doesnotexist" is not writable.');

        Writer::forFile('doesnotexist');
    }
}
