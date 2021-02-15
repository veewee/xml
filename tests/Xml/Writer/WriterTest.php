<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Writer\Writer;
use function Safe\tempnam;
use function VeeWee\Xml\Writer\Builder\document;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\children;
use function VeeWee\Xml\Writer\Builder\namespaced_attribute;
use function VeeWee\Xml\Writer\Builder\namespaced_element;
use function VeeWee\Xml\Writer\Builder\value;

class WriterTest extends TestCase
{
    /** @test */
    public function it_can_generate_a_shitload_of_xml_tags_without_memory_issues(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'xmlwriter');
        fwrite(STDOUT,  $file);

        $writer = Writer::forFile($file);
        $writer->write(
            document('1.0', 'UTF-8', children([
                element('root', children(
                    $this->provideFizzBuzzTags()
                ))
            ]))
        );


        echo file_get_contents($file);

        var_dump(filesize($file) / (1024**2) . 'MB');

        unlink($file);
    }

    private function provideFizzBuzzTags(): \Generator
    {
        //$amount = 10 * (1024 ** 2);
        $amount = (1024 ** 2);

        $namespace = 'http://fizzbuzz.com';

        for ($i=1; $i<$amount; $i++) {
            yield match (true) {
                $i%3 === 0 && $i%5 === 0 => element('FizzBuzz'),
                $i%5 === 0 => element('Buzz'),
                $i%3 === 0 => element('Fizz'),
                default => namespaced_element($namespace, 'fizzbizz:num', namespaced_attribute($namespace, 'fizzbizz:value', (string) $i))
            };
        }
    }
}
