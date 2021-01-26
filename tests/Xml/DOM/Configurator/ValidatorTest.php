<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Configurator;

use function VeeWee\Xml\DOM\Configurator\withValidator;
use function HappyHelpers\results\result;
use HappyHelpers\results\Types\Result;
use HappyHelpers\Tests\Helper\xml\LibXmlErrorProvidingTrait;
use HappyHelpers\xml\Exception\XmlErrorsException;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\Configurator\withValidator()
 *
 * @uses ::HappyHelpers\iterables\map
 * @uses ::HappyHelpers\iterables\toList
 * @uses \HappyHelpers\results\Types\Failure
 * @uses \HappyHelpers\results\Types\Ok
 * @uses ::HappyHelpers\results\result
 * @uses ::HappyHelpers\results\handler\rethrow
 * @uses ::HappyHelpers\strings\stringFromIterable
 * @uses \HappyHelpers\xml\Exception\XmlErrorsException
 * @uses ::HappyHelpers\xml\formatError
 * @uses ::HappyHelpers\xml\formatLevel
 */
class validatorTest extends TestCase
{
    use LibXmlErrorProvidingTrait;

    /** @test */
    public function it_can_configure_xml_with_valid_validation_result(): void
    {
        $doc = new \DOMDocument();
        $validator = function (\DOMDocument $doc): Result {
            return result(true);
        };

        $callable = withValidator($validator);
        self::assertIsCallable($callable);

        $result = $callable($doc);
        self::assertSame($doc, $result);
    }

    /** @test */
    public function it_can_configure_xml_with_invalid_validation_result(): void
    {
        $doc = new \DOMDocument();
        $exception = XmlErrorsException::fromXmlErrors([$this->createError(LIBXML_ERR_FATAL)]);
        $validator = fn (\DOMDocument $doc): Result => result($exception);

        $callable = withValidator($validator);
        self::assertIsCallable($callable);

        $this->expectException(XmlErrorsException::class);
        $this->expectExceptionMessage($exception->getMessage());
        $callable($doc);
    }
}
