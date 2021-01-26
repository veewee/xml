<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Validator;

use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\ErrorHandling\Issue\Level;
use VeeWee\Xml\Tests\ErrorHandling\Issue\UseIssueTrait;
use PHPUnit\Framework\TestCase;

use function VeeWee\Xml\Dom\Validator\validator_chain;

class ValidatorChainTest extends TestCase
{
    use UseIssueTrait;

    /**
     * @test
     * @dataProvider provideErrorCases
     */
    public function it_can_validate_multiple_validators(callable $validator, int $errors): void
    {
        $doc = new DOMDocument();
        $issues = $validator($doc);

        self::assertCount($errors, $issues);
    }

    public function provideErrorCases()
    {
        yield 'noFails' => [
            'validator' => validator_chain(
                fn (DOMDocument $document) => new IssueCollection(),
                fn (DOMDocument $document) => new IssueCollection(),
                fn (DOMDocument $document) => new IssueCollection()
            ),
            'errors' => 0,
        ];
        yield 'oneFails' => [
            'validator' => validator_chain(
                fn (DOMDocument $document) => new IssueCollection(),
                fn (DOMDocument $document) => new IssueCollection(
                    $this->createIssue(Level::fatal())
                ),
                fn (DOMDocument $document) => new IssueCollection(),
                fn (DOMDocument $document) => new IssueCollection()
            ),
            'errors' => 1,
        ];
        yield 'allFails' => [
            'validator' => validator_chain(
                fn (DOMDocument $document) => new IssueCollection(
                    $this->createIssue(Level::fatal())
                ),
                fn (DOMDocument $document) => new IssueCollection(
                    $this->createIssue(Level::fatal()),
                    $this->createIssue(Level::fatal())
                ),
            ),
            'errors' => 3,
        ];
    }
}
