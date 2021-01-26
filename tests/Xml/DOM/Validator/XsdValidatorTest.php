<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Validator;

use function VeeWee\Xml\Dom\Validator\xsd_validator;
use PHPUnit\Framework\TestCase;

class XsdValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideSchemeValidation
     */
    public function it_can_validate_xsds(string $xml, string $xsd, int $errors): void
    {
        $doc = new \DOMDocument();
        $doc->load($this->getFixture($xml));
        $validator = xsd_validator($this->getFixture($xsd));
        $issues = $validator($doc);

        if (!$errors) {
            self::assertCount(0, $issues);
            return;
        }

        self::assertCount($errors, $issues);
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
