<?php

declare(strict_types=1);

namespace VeeWee\Tests\Stress\Memory;

use Generator;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Writer\Writer;
use function Safe\tempnam;
use function VeeWee\Xml\Writer\Builder\children;
use function VeeWee\Xml\Writer\Builder\document;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\namespace_attribute;
use function VeeWee\Xml\Writer\Builder\prefixed_attribute;
use function VeeWee\Xml\Writer\Builder\prefixed_element;
use function VeeWee\Xml\Writer\Configurator\indentation;

final class ReadWriteTest extends TestCase
{
    private string $file = '';

    protected function setUp(): void
    {
        $this->file = tempnam(sys_get_temp_dir(), 'xmlwriter');
    }

    protected function tearDown(): void
    {
        @unlink($this->file);
    }

    public function test_it_can_handle_a_shitload_of_xml(): void
    {
        $writer = Writer::forFile($this->file, indentation('  '));
        $writer->write(
            document('1.0', 'UTF-8', children([
                element('root', namespace_attribute('http://fizzbuzz.com', 'fizzbuzz'), children(
                    $this->provideFizzBuzzTags()
                ))
            ]))
        );

        $size = filesize($this->file) / (1024**2);
        static::assertGreaterThan(200, $size);
    }

    private function provideFizzBuzzTags(): Generator
    {
        //$amount = 10 * (1024 ** 2);
        $amount = 10 * (1024 ** 2);

        for ($i=1; $i<$amount; $i++) {
            yield match (true) {
                $i%3 === 0 && $i%5 === 0 => element('FizzBuzz'),
                $i%5 === 0 => element('Buzz'),
                $i%3 === 0 => element('Fizz'),
                default => prefixed_element('fizzbuzz', 'num', prefixed_attribute('fizzbuzz', 'value', (string) $i))
            };
        }
    }
}
