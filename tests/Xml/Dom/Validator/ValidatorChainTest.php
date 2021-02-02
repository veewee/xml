<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Validator;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\ErrorHandling\Issue\Level;
use VeeWee\Xml\Tests\ErrorHandling\Issue\UseIssueTrait;

use function VeeWee\Xml\Dom\Validator\validator_chain;

final class ValidatorChainTest extends TestCase
{
    use UseIssueTrait;

    /**
     *
     * @dataProvider provideErrorCases
     */
    public function test_it_can_validate_multiple_validators(callable $validator, int $errors): void
    {
        $doc = new DOMDocument();
        $issues = $validator($doc);

        static::assertCount($errors, $issues);
    }

    public function provideErrorCases()
    {
        yield 'noFails' => [
            'validator' => validator_chain(
                static fn (DOMDocument $document) => new IssueCollection(),
                static fn (DOMDocument $document) => new IssueCollection(),
                static fn (DOMDocument $document) => new IssueCollection()
            ),
            'errors' => 0,
        ];
        yield 'oneFails' => [
            'validator' => validator_chain(
                static fn (DOMDocument $document) => new IssueCollection(),
                fn (DOMDocument $document) => new IssueCollection(
                    $this->createIssue(Level::fatal())
                ),
                static fn (DOMDocument $document) => new IssueCollection(),
                static fn (DOMDocument $document) => new IssueCollection()
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
