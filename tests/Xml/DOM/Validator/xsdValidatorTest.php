<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\validator;

use function VeeWee\Xml\DOM\validator\xsdValidator;
use HappyHelpers\xml\Exception\XmlErrorsException;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\validator\xsdValidator()
 *
 * @uses ::HappyHelpers\assertions\assertExtensionLoaded
 * @uses ::HappyHelpers\iterables\map
 * @uses ::HappyHelpers\iterables\toList
 * @uses \HappyHelpers\results\Types\Failure
 * @uses \HappyHelpers\results\Types\Ok
 * @uses ::HappyHelpers\results\tryResultFrom
 * @uses ::HappyHelpers\strings\stringFromIterable
 * @uses \HappyHelpers\xml\Exception\XmlErrorsException
 * @uses ::HappyHelpers\xml\detectXmlErrors
 * @uses ::HappyHelpers\xml\formatError
 * @uses ::HappyHelpers\xml\formatLevel
 * @uses ::HappyHelpers\xml\useInternalErrors
 */
class xsdValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideSchemeValidation
     */
    public function it_can_validate_xsds(string $xml, string $xsd, int $errors): void
    {
        $doc = new \DOMDocument();
        $doc->load($this->getFixture($xml));
        $validator = xsdValidator($this->getFixture($xsd));
        $result = $validator($doc);

        if (!$errors) {
            self::assertTrue($result->isOk());
            self::assertTrue($result->value());

            return;
        }

        self::assertFalse($result->isOk());
        self::assertInstanceOf(XmlErrorsException::class, $result->value());
        self::assertCount($errors, $result->value()->errors());
    }

    /**
     * @return array
     */
    public function provideSchemeValidation()
    {
        yield 'valid' => [
            'xml' => 'xml-valid.xml',
            'xsd' => 'note-nonamespace.xsd',
            'errors' => 0,
        ];
        yield 'valid-namespace' => [
            'xml' => 'xsd-namespace-valid.xml',
            'xsd' => 'note-namespace.xsd',
            'errors' => 0,
        ];
        yield 'invalid-namespace' => [
            'xml' => 'xsd-namespace-invalid.xml',
            'xsd' => 'note-nonamespace.xsd',
            'errors' => 1,
        ];
        yield 'valid-no-namespace' => [
            'xml' => 'xsd-nonamespace-valid.xml',
            'xsd' => 'note-nonamespace.xsd',
            'errors' => 0,
        ];
        yield 'invalid-no-namespace' => [
            'xml' => 'xsd-nonamespace-invalid.xml',
            'xsd' => 'note-nonamespace.xsd',
            'errors' => 1,
        ];
    }

    private function getFixture(string $fixture): string
    {
        $file = FIXTURE_DIR.'/dom/validator/xsd/'.$fixture;
        self::assertFileExists($file);

        return $file;
    }
}
