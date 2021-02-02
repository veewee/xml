<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Validator;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Validator\xsd_validator;

final class XsdValidatorTest extends TestCase
{
    /**
     *
     * @dataProvider provideSchemeValidation
     */
    public function testIt_can_validate_xsds(string $xml, string $xsd, int $errors): void
    {
        $doc = new DOMDocument();
        $doc->load($this->getFixture($xml));
        $validator = xsd_validator($this->getFixture($xsd));
        $issues = $validator($doc);

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
