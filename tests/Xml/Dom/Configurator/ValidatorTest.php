<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Configurator;

use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\ErrorHandling\Issue\Level;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Tests\ErrorHandling\Issue\UseIssueTrait;
use function VeeWee\Xml\Dom\Configurator\validator;
use function VeeWee\Xml\DOM\Configurator\withValidator;
use function HappyHelpers\results\result;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    use UseIssueTrait;

    /** @test */
    public function it_can_configure_xml_with_valid_validation_result(): void
    {
        $doc = new DOMDocument();
        $validator = validator(static fn (DOMDocument $doc): IssueCollection  => new IssueCollection());

        $result = $validator($doc);
        self::assertSame($doc, $result);
    }

    /** @test */
    public function it_can_configure_xml_with_invalid_validation_result(): void
    {
        $doc = new DOMDocument();
        $validator = validator(fn (DOMDocument $doc): IssueCollection  => new IssueCollection(
            $this->createIssue(Level::fatal())
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid XML');
        $result = $validator($doc);
    }
}
