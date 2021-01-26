<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\validator;

use DOMDocument;
use function VeeWee\Xml\DOM\validator\chainedValidator;
use function HappyHelpers\results\result;
use HappyHelpers\Tests\Helper\xml\LibXmlErrorProvidingTrait;
use HappyHelpers\xml\Exception\XmlErrorsException;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\validator\chainedValidator()
 *
 * @uses ::HappyHelpers\assertions\assertExtensionLoaded
 * @uses ::HappyHelpers\iterables\map
 * @uses ::HappyHelpers\iterables\toList
 * @uses \HappyHelpers\results\Types\Failure
 * @uses \HappyHelpers\results\Types\Ok
 * @uses ::HappyHelpers\results\result
 * @uses ::HappyHelpers\strings\stringFromIterable
 * @uses \HappyHelpers\xml\Exception\XmlErrorsException
 * @uses ::HappyHelpers\xml\detectXmlErrors
 * @uses ::HappyHelpers\xml\formatError
 * @uses ::HappyHelpers\xml\formatLevel
 * @uses ::HappyHelpers\xml\useInternalErrors
 */
class chainedValidatorTest extends TestCase
{
    use LibXmlErrorProvidingTrait;

    /** @test */
    public function it_returns_ok_if_there_are_no_errors_to_be_found(): void
    {
        $doc = new DOMDocument();
        $validator = chainedValidator(
            fn (DOMDocument $document) => result(true),
            fn (DOMDocument $document) => result(true),
            fn (DOMDocument $document) => result(true),
            fn (DOMDocument $document) => result(true)
        );
        $result = $validator($doc);

        self::assertTrue($result->isOk());
        self::assertTrue($result->value());
    }

    /**
     * @test
     * @dataProvider provideErrorCases
     */
    public function it_returns_false_if_one_link_fails(callable $validator, int $errors): void
    {
        $doc = new DOMDocument();
        $result = $validator($doc);

        self::assertFalse($result->isOk());
        self::assertInstanceOf(XmlErrorsException::class, $result->value());
        self::assertCount($errors, $result->value()->errors());
    }

    public function provideErrorCases()
    {
        yield 'oneFails' => [
            'validator' => chainedValidator(
                fn (DOMDocument $document) => result(true),
                fn (DOMDocument $document) => result(XmlErrorsException::fromXmlErrors([
                    $this->createError(LIBXML_ERR_FATAL),
                ])),
                fn (DOMDocument $document) => result(true),
                fn (DOMDocument $document) => result(true)
            ),
            'errors' => 1,
        ];
        yield 'allFails' => [
            'validator' => chainedValidator(
                fn (DOMDocument $document) => result(XmlErrorsException::fromXmlErrors([
                    $this->createError(LIBXML_ERR_FATAL),
                ])),
                fn (DOMDocument $document) => result(XmlErrorsException::fromXmlErrors([
                    $this->createError(LIBXML_ERR_FATAL),
                    $this->createError(LIBXML_ERR_FATAL),
                ]))
            ),
            'errors' => 3,
        ];
    }
}
