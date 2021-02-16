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
        $this->expectErrorMessage('The file "invalid-file" does not exist.');

        xml_file_loader('invalid-file')();
    }
}
