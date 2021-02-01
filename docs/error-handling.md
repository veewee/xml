# ErrorHandling Component

The biggest advantage of this package is that it deals with all XML errors for you!
Normally, the PHP API would return false and triggers PHP warnings.
We provide the tools to deal with this and to get a clear Exception that tells you what is wrong, so that you can focus on fixing the issue! 

All our components are using the ErrorHandling component.
If you want to extend the provided functionality, you can always use our error handling tools as well!

The ErrorHandling component consists out of following blocks:

* [Assertions](#assertions)
* [Blockers](#blockers)
* [Detectors](#detectors)
* [Issues](#issues)


## Assertions

Assertions validate input and throw an `InvalidArgumentException` if the input is invalid.

#### assert_strict_prefixed_name

Some functions require you to pass a strict prefixed name: `prefix:localName="someting"`.
This assertions validates it for you!

```php
assert_strict_qualified_name($qualifiedName);
```

## Blockers

Blockers don't mess around with errors ... !

#### disallow_issues

This function takes a `callable` as input and fetches all triggered XML specific warnings.
If the provided function throws any Exception, it will wrap the detected issues with it and rethrow that new exception!
You will frequently use it together with `disallow_libxml_false_returns` to make sure the result type is safe to use!

```php
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/** @var true $result */
$result = disallow_issues(
    static fn (): bool => disallow_libxml_false_returns(
        $document->load('some-file.xml'),
        'Could not load the DOM Document'
    )
);
```

#### disallow_libxml_false_returns

In PHP, a lot of XML related functions return false.
This function deals with the false return and throws an `InvalidArgumentException` if that is the case.
You must provide a clear and helpful error message for failure cases:

```php
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/** @var true $result */
$result = disallow_libxml_false_returns(
    $document->load('some-file.xml'),
    'Could not load the DOM Document'
);
```

#### stop_on_first_issue

This function can be used in situations where you want to wrap error handling on lazy iterators (generators).
Before every tick, it will wrap error handling around the executed functionality.
Once the tick is done, it will check for errors and throw an exception if that's the case:

```php
use function VeeWee\Xml\ErrorHandling\stop_on_first_issue;

/** @var Generator<string> $result */
$result = stop_on_first_issue(
    static fn(): bool => $reader->read(),
    static fn(): ?string =>
        $reader->nodeType === XMLReader::ELEMENT && $reader->name === 'user'
            ? $reader->readOuterXml() ?: null
            : null,
);
```

## Detectors

In some cases, you don't want errors to block.
Especially in situations where you want to validate the XML document based on XSD, DTD, ...

#### detect_issues

This function detects XML issues in a provided callback function.
It returns both the result of the executed function and the detected issues.

```php
use Psl\Result\Success;
use Psl\Result\Failure;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\Exception\ExceptionInterface;
use function VeeWee\Xml\ErrorHandling\detect_issues;

/** @var Success<bool>|Failure<ExceptionInterface> $result */
/** @var IssueCollection $issues */

[$result, $issues] = detect_issues(
    static fn () => $document->schemaValidate($xsd)
);
```

## Issues

An issue is what we call an XML error.
It looks like this:

```php
namespace VeeWee\Xml\ErrorHandling\Issue;

#[Immutable]
final class Issue
{
    public function level(): Level;
    public function code(): int;
    public function column(): int;
    public function message(): string;
    public function file(): string;
    public function line(): int;

    public function toString(): string;
}
```

The Level is an enum with following supported values:

```php
namespace VeeWee\Xml\ErrorHandling\Issue;

#[Immutable]
final class Level
{
    public static function error(): self;
    public static function fatal(): self;
    public static function warning(): self;
}
```

This component often gives you a collection of issues to work with:

```php
namespace VeeWee\Xml\ErrorHandling\Issue;

#[Extends iterable<Issue>]
#[Immutable]
final class IssueCollection implements Countable, IteratorAggregate
{
    // Check the source code for a list of all functions you can use on this collection! 
}
```
