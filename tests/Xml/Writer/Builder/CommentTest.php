<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\comment;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\raw;
use function VeeWee\Xml\Writer\Builder\value;

final class CommentTest extends TestCase
{
    use UseInMemoryWriterTrait;

    public function test_it_can_create_empty_comment(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(comment());
        });

        static::assertSame('<!---->', $result);
    }

    public function test_it_can_create_comment_with_unescaped_raw_value(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                comment(raw('<hello>world</hello>'))
            );
        });

        static::assertSame('<!--<hello>world</hello>-->', $result);
    }

    public function test_it_can_create_comment_with_unescaped_text_value(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                comment(value('<hello>world</hello>'))
            );
        });

        static::assertSame('<!--<hello>world</hello>-->', $result);
    }

    public function test_it_can_create_comment_inside_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('root', comment(value('<hello>world</hello>')))
            );
        });

        static::assertSame('<root><!--<hello>world</hello>--></root>', $result);
    }
}
