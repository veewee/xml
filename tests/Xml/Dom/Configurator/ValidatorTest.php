<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\ErrorHandling\Issue\UseIssueTrait;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\ErrorHandling\Issue\Level;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Configurator\validator;

final class ValidatorTest extends TestCase
{
    use UseIssueTrait;

    
    public function test_it_can_configure_xml_with_valid_validation_result(): void
    {
        $doc = new DOMDocument();
        $validator = validator(static fn (DOMDocument $doc): IssueCollection => new IssueCollection());

        $result = $validator($doc);
        static::assertSame($doc, $result);
    }

    
    public function test_it_can_configure_xml_with_invalid_validation_result(): void
    {
        $doc = new DOMDocument();
        $validator = validator(fn (DOMDocument $doc): IssueCollection => new IssueCollection(
            $this->createIssue(Level::fatal())
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid XML');
        $result = $validator($doc);
    }
}
