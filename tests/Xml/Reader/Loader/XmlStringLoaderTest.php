<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Loader;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Reader\Loader\xml_string_loader;

class XmlStringLoaderTest extends TestCase
{
    /** @test */
    public function it_can_handle_invalid_string_loader(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('The provided XML can not be empty!');

        xml_string_loader('')();
    }
}
