<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Validator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Validator\xsd_validator;

final class XsdValidatorTest extends TestCase
{
    /**
     *
     * @dataProvider provideSchemeValidation
     */
    public function test_it_can_validate_xsds(string $xml, string $xsd, int $errors): void
    {
        $doc = Document::fromXmlFile($this->getFixture($xml));
        $validator = xsd_validator($this->getFixture($xsd));

        $issues = $doc->validate($validator);

        if (!$errors) {
            static::assertCount(0, $issues);
            return;
        }

        static::assertCount($errors, $issues);
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
        static::assertFileExists($file);

        return $file;
    }
}
