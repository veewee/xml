# Migration Guide: veewee/xml v3 to v4

This document provides a complete set of migration rules for upgrading from `veewee/xml` v3.x to v4.x.
The primary driver behind v4 is PHP 8.4's new spec-compliant DOM API (`Dom\` namespace), which replaces the legacy `DOM` extension classes.

## Prerequisites

- **PHP 8.4+** is required (v3 supported PHP 8.2/8.3)
- The new `Dom\` namespace classes are part of PHP 8.4's opt-in DOM spec compliance ([RFC](https://wiki.php.net/rfc/opt_in_dom_spec_compliance))

## Recommended Upgrade Path

| veewee/xml | PHP           | LTS |
|------------|---------------|-----|
| 3.0 - 3.1 | 8.1, 8.2, 8.3 | NO  |
| 3.2        | 8.2, 8.3      | NO  |
| 3.3+       | 8.2, 8.3, 8.4 | YES |
| 4.0+       | 8.4+          | YES |

**Step 1:** Upgrade to v3.3+ and PHP 8.4 first (v3.3+ supports PHP 8.4 as a bridge).
**Step 2:** Apply the migration rules below and bump to v4.0.

## composer.json

```diff
- "php": "~8.2.0 || ~8.3.0",
+ "php": "~8.4.0",
```

---

## 1. DOM Class Replacements

The entire DOM layer moved from legacy `DOM*` classes to the new `Dom\` namespace.

### Important: Naming Overlap

PHP 8.4 introduces `Dom\Document` as a base class (extended by `Dom\XMLDocument` and `Dom\HTMLDocument`).
The veewee wrapper is `VeeWee\Xml\Dom\Document`. These are different classes in different namespaces.

**Most consuming code only needs `VeeWee\Xml\Dom\Document`** as the entry point and does not need to import native `Dom\*` classes directly. The native types only surface when:

- You define **custom configurators, builders, validators, or loaders** (their callable signatures changed, e.g. `callable(DOMDocument): DOMDocument` became `callable(Dom\XMLDocument): Dom\XMLDocument`)
- You call **`toUnsafeDocument()`** and pass the result around (now returns `Dom\XMLDocument`)
- You type-hint **DOM nodes** in your own code (e.g. `DOMElement` in function parameters)

If your code exclusively uses the veewee `Document` wrapper and its provided functions, the migration is mostly updating `veewee/xml` in `composer.json` and fixing any custom callables.

### Search and Replace Rules

When you do need to update native DOM type references, apply these replacements. Order matters: replace longer/more specific strings first to avoid partial matches.

| v3 (Search) | v4 (Replace) |
|---|---|
| `DOMCdataSection` | `Dom\CDATASection` |
| `DOMDocument` | `Dom\XMLDocument` |
| `DOMNodeList` | `Dom\NodeList` |
| `DOMElement` | `Dom\Element` |
| `DOMXPath` | `Dom\XPath` |
| `DOMNode` | `Dom\Node` |
| `DOMAttr` | `Dom\Attr` |

> **Note:** `DOMDocument` does NOT become `Dom\Document`. It becomes `Dom\XMLDocument` specifically. `Dom\Document` is the abstract base class in PHP 8.4; you almost never reference it directly.
> **Note:** `DOMCdataSection` becomes `Dom\CDATASection` (capitalization differs).

### Import Statement Changes

```diff
- use DOMDocument;
- use DOMElement;
- use DOMNode;
- use DOMXPath;
- use DOMNodeList;
- use DOMAttr;
- use DOMCdataSection;
+ use Dom\XMLDocument;
+ use Dom\Element;
+ use Dom\Node;
+ use Dom\XPath;
+ use Dom\NodeList;
+ use Dom\Attr;
+ use Dom\CDATASection;
```

---

## 2. XMLDocument Creation (No More `new DOMDocument()`)

Under the hood, `Dom\XMLDocument` cannot be instantiated with `new`. This is handled internally by veewee/xml's loaders, so **if you load documents through the `Document` wrapper, no action is needed**.

Only relevant if your code creates `DOMDocument` instances directly (bypassing the wrapper):

```diff
- $document = new DOMDocument();
+ $document = Dom\XMLDocument::createEmpty();

- $document = new DOMDocument();
- $document->loadXML($xml, $options);
+ $document = Dom\XMLDocument::createFromString($xml, $options);

- $document = new DOMDocument();
- $document->load($file, $options);
+ $document = Dom\XMLDocument::createFromFile($file, $options);
```

Both `createFromString` and `createFromFile` accept an optional third parameter `?string $override_encoding`.

---

## 3. Document Loading Pattern Change

### Loader signature change

Loaders no longer receive a `DOMDocument` and mutate it. They now return a new `XMLDocument`:

```diff
- /** @return Closure(DOMDocument): void */
+ /** @return Closure(): XMLDocument */
```

### `load()` helper removed

The internal `VeeWee\Xml\Dom\Loader\load()` function has been removed. Loaders now directly use `XMLDocument::createFromString()` / `createFromFile()` wrapped in `disallow_issues()`.

### `loader()` configurator removed

`VeeWee\Xml\Dom\Configurator\loader()` has been removed. Use `Document::fromLoader()` instead.

```diff
- use function VeeWee\Xml\Dom\Configurator\loader;
- use function VeeWee\Xml\Dom\Loader\xml_string_loader;
-
- Document::configure(
-     loader(xml_string_loader($xml)),
-     ...configurators
- );
+ use function VeeWee\Xml\Dom\Loader\xml_string_loader;
+
+ Document::fromLoader(
+     xml_string_loader($xml),
+     ...configurators
+ );
```

### New `Document::fromLoader()` method

All static factory methods (`fromXmlFile`, `fromXmlString`, `fromXmlNode`, `fromUnsafeDocument`) now delegate to the new `fromLoader()` method:

```php
Document::fromLoader(callable $loader, callable ...$configurators): self
```

---

## 4. `preserveWhiteSpace` Property Removed

`Dom\XMLDocument` no longer has a `preserveWhiteSpace` property.

### `pretty_print()` configurator

Now re-parses the document with `LIBXML_NOBLANKS` flag and sets `formatOutput = true`:

```diff
- $document->preserveWhiteSpace = false;
- $document->formatOutput = true;
+ // Use the configurator: pretty_print()
+ // Or manually:
+ $trimmed = XMLDocument::createFromString($xml, LIBXML_NOBLANKS);
+ $trimmed->formatOutput = true;
```

### `trim_spaces()` configurator

Similarly re-parses with `LIBXML_NOBLANKS` and sets `formatOutput = false`.

### New `format_output()` configurator

A new configurator `VeeWee\Xml\Dom\Configurator\format_output()` was added for simple `formatOutput` toggling:

```php
use function VeeWee\Xml\Dom\Configurator\format_output;

Document::fromLoader(
    xml_file_loader('data.xml', LIBXML_NOBLANKS, 'UTF-8'),
    format_output($debug),
);
```

---

## 5. XMLReader Changes

If you use `Reader::fromXmlFile()` or `Reader::fromXmlString()`, these continue to work unchanged. The changes below are relevant if you use the loaders directly or the native `XMLReader` class.

### Static factory methods replace legacy constructors (native PHP)

Only relevant if your code uses `XMLReader` directly (bypassing the veewee `Reader` wrapper):

```diff
- XMLReader::open($file)
+ XMLReader::fromUri($file, $encoding, $flags)

- XMLReader::XML($xml)
+ XMLReader::fromString($xml, $encoding, $flags)
```

### New stream support

```php
use VeeWee\Xml\Reader\Reader;

$reader = Reader::fromXmlStream($stream, ...$configurators);
```

### Loader signature accepts additional parameters

```diff
- xml_file_loader(string $file): Closure
+ xml_file_loader(string $file, ?string $encoding = null, int $flags = 0): Closure

- xml_string_loader(string $xml): Closure
+ xml_string_loader(string $xml, ?string $encoding = null, int $flags = 0): Closure
```

---

## 6. XMLWriter Changes

If you use `Writer::inMemory()` or `Writer::forFile()`, these continue to work unchanged. The changes below are relevant if you use custom openers or the native `XMLWriter` class.

### Opener interface change

Openers no longer receive an XMLWriter and return bool. They now return a new XMLWriter. Only relevant if you implemented custom openers:

```diff
- public function __invoke(XMLWriter $writer): bool;
+ public function __invoke(): XMLWriter;
```

### Static factory methods replace legacy constructors (native PHP)

Only relevant if your code uses `XMLWriter` directly (bypassing the veewee `Writer` wrapper):

```diff
- $writer = new XMLWriter();
- $writer->openMemory();
+ $writer = XMLWriter::toMemory();

- $writer = new XMLWriter();
- $writer->openUri($file);
+ $writer = XMLWriter::toUri($file);
```

### `open()` configurator removed

`VeeWee\Xml\Writer\Configurator\open()` has been removed.

### `Writer::configure()` now requires an opener as first argument

```diff
- Writer::configure(open(memory_opener()), ...$configurators);
+ Writer::configure(memory_opener(), ...$configurators);
```

### New stream opener

```php
use VeeWee\Xml\Writer\Writer;

$writer = Writer::forStream($stream, ...$configurators);
```

### New `flush()` applicative

```php
use function VeeWee\Xml\Writer\Applicative\flush;

$writer->apply(flush());
```

---

## 7. XPath and XSLT Function Registration

### `functions()` parameter type changed

The `functions()` configurator now accepts an associative array of callbacks instead of a list of function name strings:

```diff
- use function VeeWee\Xml\Dom\Xpath\Configurator\functions;
-
- /** @param non-empty-list<string> $functions */
- functions(['myFunction'])
+ use function VeeWee\Xml\Dom\Xpath\Configurator\functions;
+
+ /** @param array<string, callable(mixed...): mixed> $functions */
+ functions(['myFunction' => my_function(...)])
```

This same change applies to `VeeWee\Xml\Xslt\Configurator\functions()`.

### New `namespaced_functions()` configurators

New configurators for registering functions under specific XML namespaces:

```php
// XPath
use function VeeWee\Xml\Dom\Xpath\Configurator\namespaced_functions;

namespaced_functions('http://my-ns.com', 'myprefix', [
    'myFunction' => my_function(...),
]);

// XSLT
use function VeeWee\Xml\Xslt\Configurator\namespaced_functions;

namespaced_functions('http://my-ns.com', [
    'myFunction' => my_function(...),
]);
```

---

## 8. Nullable Parameter Syntax

PHP 8.4 explicit nullable types are now used throughout:

```diff
- function query(string $query, DOMNode $node = null): Closure
+ function query(string $query, ?Node $node = null): Closure
```

This is not a behavioral change, but may affect static analysis tools.

---

## 9. Node Manipulation

### `rename()` method available on elements/attributes

The new DOM spec provides a native `rename()` method on elements and attributes.
The library's manipulator functions leverage this internally. No user action required unless you were calling internal manipulation functions directly.

### New `rename_element_namespace()` function

```php
use function VeeWee\Xml\Dom\Manipulator\Xmlns\rename_element_namespace;

rename_element_namespace($element, $namespaceURI, $newPrefix);
```

---

## 10. `canonicalize()` Configurator

The implementation changed but the API remains the same. Internally it no longer uses `loader()`:

```diff
- Document::configure(
-     pretty_print(),
-     loader(xml_string_loader($document->C14N(), LIBXML_NSCLEAN + LIBXML_NOCDATA)),
-     normalize()
- )
+ Document::fromLoader(
+     xml_string_loader($document->C14N(), LIBXML_NSCLEAN + LIBXML_NOCDATA),
+     pretty_print(),
+     normalize()
+ )
```

No user action required unless you wrote custom configurators following a similar pattern.

---

## Quick Reference: Removed Functions

| Removed | Replacement |
|---|---|
| `VeeWee\Xml\Dom\Configurator\loader()` | Use `Document::fromLoader()` |
| `VeeWee\Xml\Dom\Loader\load()` | Internal, use `disallow_issues()` + `XMLDocument::createFrom*()` |
| `VeeWee\Xml\Writer\Configurator\open()` | Pass opener directly to `Writer::configure()` |

## Quick Reference: New Functions/Classes

| New | Purpose |
|---|---|
| `Document::fromLoader()` | Load XML via a callable that returns `XMLDocument` |
| `Configurator\format_output()` | Toggle `formatOutput` on document |
| `Reader::fromXmlStream()` | Read XML from a stream resource |
| `Reader\Loader\xml_stream_loader()` | Stream-based reader loader |
| `Writer::forStream()` | Write XML to a stream resource |
| `Writer\Opener\xml_stream_opener()` | Stream-based writer opener |
| `Writer\Applicative\flush()` | Flush writer buffer |
| `Xpath\Configurator\namespaced_functions()` | Register namespaced XPath callback functions |
| `Xslt\Configurator\namespaced_functions()` | Register namespaced XSLT callback functions |
| `Manipulator\Xmlns\rename_element_namespace()` | Rename element namespace prefix |

---

## Automated Migration Checklist

Use this checklist when migrating a project:

- [ ] Update `composer.json` to require `php: ~8.4.0` and `veewee/xml: ^4.0`
- [ ] Search-replace all DOM class references (see table in section 1)
- [ ] Replace `new DOMDocument()` with `XMLDocument::createEmpty()`, `createFromString()`, or `createFromFile()`
- [ ] Replace `Configurator\loader(xml_*_loader(...))` with `Document::fromLoader(xml_*_loader(...))`
- [ ] Remove `use function VeeWee\Xml\Dom\Configurator\loader`
- [ ] Remove `use function VeeWee\Xml\Dom\Loader\load`
- [ ] Replace `$document->preserveWhiteSpace = false` with `LIBXML_NOBLANKS` flag on loaders or use `trim_spaces()`/`pretty_print()` configurators
- [ ] Update XMLReader: `::open()` to `::fromUri()`, `::XML()` to `::fromString()`
- [ ] Update XMLWriter: `new XMLWriter()` + `openMemory()`/`openUri()` to `XMLWriter::toMemory()`/`::toUri()`
- [ ] Remove `use function VeeWee\Xml\Writer\Configurator\open`
- [ ] Update `Writer::configure()` calls: pass opener as first argument directly
- [ ] Update `functions()` configurator calls: change from `['funcName']` to `['funcName' => $callback]`
- [ ] Update nullable parameter syntax if your static analysis requires it
- [ ] Run static analysis and tests to catch any remaining type mismatches
