<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Writer\Writer;
use function Safe\tempnam;
use function VeeWee\Xml\Writer\Builder\document;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\nodes;
use function VeeWee\Xml\Writer\Builder\value;

class WriterTest extends TestCase
{
    /** @test */
    public function it_can_generate_a_shitload_of_xml_tags_without_memory_issues(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'xmlwriter');
        $writer = Writer::forFile($file);
        $writer->write(
            document('1.0', 'UTF-8', nodes([
                element('root', nodes(
                    $this->provide1GFizzBuzzTags()
                ))
            ]))
        );

        echo $file;
        echo file_get_contents($file);

        unlink($file);
    }

    private function provide1GFizzBuzzTags(): \Generator
    {
        $oneGig = 1024 ** 3;
        for ($i=1; $i<$oneGig; $i++) {
            yield match (true) {
                $i%3 === 0 && $i%5 === 0 => element('FizzBuzz'),
                $i%5 === 0 => element('Buzz'),
                $i%3 === 0 => element('Fizz'),
                default => element('num', value((string) $i))
            };
        }
    }
}
