<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Opener;

use PHPUnit\Framework\TestCase;
use Psl\File;
use Psl\Filesystem;
use Psl\OS;
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
            File\write($path, 'will  be truncated', File\WriteMode::TRUNCATE);

            $writer = Writer::forFile($path);
            $writer->write(element('root'));

            self::assertXmlStringEqualsXmlFile($path, '<root />');
        });
    }

    public function test_it_can_write_to_a_new_file(): void
    {
        $temporaryFile = Filesystem\create_temporary_file();
        Filesystem\delete_file($temporaryFile);

        $writer = Writer::forFile($temporaryFile);
        $writer->write(element('root'));
        static::assertXmlStringEqualsXmlFile($temporaryFile, '<root />');

        unlink($temporaryFile);
    }

    public function test_it_errors_if_file_is_not_writable(): void
    {
        if (OS\is_windows()) {
            static::markTestSkipped('Permissions are not reliable on windows.');
        }

        $temporary_file = Filesystem\create_temporary_file();
        Filesystem\delete_file($temporary_file);
        Filesystem\create_directory($temporary_file);
        Filesystem\change_permissions($temporary_file, 0555);

        $file = $temporary_file . Filesystem\SEPARATOR . 'foo';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File "' . $file . '" is not writable.');

        Writer::forFile($file);
    }
}
