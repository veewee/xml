<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Loader;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Reader\Loader\xml_string_loader;

final class XmlStringLoaderTest extends TestCase
{
    public function test_it_can_handle_invalid_string_loader(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('The provided XML can not be empty!');

        xml_string_loader('')();
    }
}
