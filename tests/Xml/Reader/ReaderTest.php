<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Reader;
use function VeeWee\Xml\Reader\Matcher\node_name;

class ReaderTest extends TestCase
{
    /** @test */
    public function it_can_provide_xml(): void
    {
        $reader = Reader::fromXmlString(
            <<<'EOXML'
                <root>
                    <user>Jos</user>
                    <user>Bos</user>
                    <user>Mos</user>    
                </root>
            EOXML
        );

        $iterator = $reader->provide(node_name('user'));

        self::assertSame(
            [
                '<user>Jos</user>',
                '<user>Bos</user>',
                '<user>Mos</user>',
            ],
            [...$iterator]
        );
    }
}
