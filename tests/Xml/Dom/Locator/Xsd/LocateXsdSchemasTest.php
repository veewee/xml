<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Xsd;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Xsd\Schema\Schema;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_all_xsd_schemas;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_namespaced_xsd_schemas;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_no_namespaced_xsd_schemas;

final class LocateAllXsdSchemasTest extends TestCase
{
    public function test_it_can_locate_namespaced_xsd_schemas(): void
    {
        $document = $this->loadXsdContainer();
        $results = locate_namespaced_xsd_schemas($document);

        static::assertEquals(
            [
                Schema::withNamespace('http://www.happy-helpers1.com', 'note-namespace1.xsd'),
                Schema::withNamespace('http://www.happy-helpers2.com', 'http://localhost/note-namespace2.xsd'),
            ],
            [...$results]
        );
    }

    
    public function test_it_can_locate_no_namespaced_xsd_schemas(): void
    {
        $document = $this->loadXsdContainer();
        $results = locate_no_namespaced_xsd_schemas($document);

        static::assertEquals(
            [
                Schema::withoutNamespace('note-nonamespace1.xsd'),
                Schema::withoutNamespace('http://localhost/note-nonamespace2.xsd'),
            ],
            [...$results]
        );
    }

    
    public function test_it_can_locate_all_xsd_schemas(): void
    {
        $document = $this->loadXsdContainer();
        $results = locate_all_xsd_schemas($document);

        static::assertEquals(
            [
                Schema::withNamespace('http://www.happy-helpers1.com', 'note-namespace1.xsd'),
                Schema::withNamespace('http://www.happy-helpers2.com', 'http://localhost/note-namespace2.xsd'),
                Schema::withoutNamespace('note-nonamespace1.xsd'),
                Schema::withoutNamespace('http://localhost/note-nonamespace2.xsd'),
            ],
            [...$results]
        );
    }

    private function loadXsdContainer(): DOMDocument
    {
        $file = FIXTURE_DIR.'/dom/locator/xsd/xsdcontainer.xml';
        static::assertFileExists($file);

        $document = new DOMDocument();
        $document->load($file);

        return $document;
    }
}
