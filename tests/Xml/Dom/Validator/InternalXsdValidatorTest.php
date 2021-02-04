<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Validator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Validator\internal_xsd_validator;
use function VeeWee\Xml\Xsd\Schema\Manipulator\base_path;

final class InternalXsdValidatorTest extends TestCase
{
    /**
     *
     * @dataProvider provideSchemeValidation
     */
    public function test_it_can_validate_internal_xsds(string $xml, int $errors): void
    {
        $file = $this->getFixture($xml);
        $doc = Document::fromXmlFile($file);
        $validator = internal_xsd_validator(
            base_path(dirname($file))
        );

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
            'errors' => 0,
        ];
        yield 'valid-namespace' => [
            'xml' => 'xsd-namespace-valid.xml',
            'errors' => 0,
        ];
        yield 'invalid-namespace' => [
            'xml' => 'xsd-namespace-invalid.xml',
            'errors' => 1,
        ];
        yield 'valid-no-namespace' => [
            'xml' => 'xsd-nonamespace-valid.xml',
            'errors' => 0,
        ];
        yield 'invalid-no-namespace' => [
            'xml' => 'xsd-nonamespace-invalid.xml',
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
