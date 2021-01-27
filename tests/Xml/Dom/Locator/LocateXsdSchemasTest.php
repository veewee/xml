<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\locator;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_all_xsd_schemas;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_namespaced_xsd_schemas;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_no_namespaced_xsd_schemas;

class LocateXsdSchemasTest extends TestCase
{
    /** @test */
    public function it_can_locate_namespaced_xsd_schemas(): void
    {
        $document = $this->loadXsdContainer();
        $results = locate_namespaced_xsd_schemas($document);

        self::assertInstanceOf(\Generator::class, $results);
        self::assertSame(
            [
                'note-namespace1.xsd',
                'http://localhost/note-namespace2.xsd',
            ],
            [...$results]
        );
    }

    /** @test */
    public function it_can_locate_no_namespaced_xsd_schemas(): void
    {
        $document = $this->loadXsdContainer();
        $results = locate_no_namespaced_xsd_schemas($document);

        self::assertInstanceOf(\Generator::class, $results);
        self::assertSame(
            [
                'note-nonamespace1.xsd',
                'http://localhost/note-nonamespace2.xsd',
            ],
            [...$results]
        );
    }

    /** @test */
    public function it_can_locate_all_xsd_schemas(): void
    {
        $document = $this->loadXsdContainer();
        $results = locate_all_xsd_schemas($document);

        self::assertInstanceOf(\Generator::class, $results);
        self::assertSame(
            [
                'note-namespace1.xsd',
                'http://localhost/note-namespace2.xsd',
                'note-nonamespace1.xsd',
                'http://localhost/note-nonamespace2.xsd',
            ],
            [...$results]
        );
    }

    private function loadXsdContainer(): DOMDocument
    {
        $file = FIXTURE_DIR.'/dom/locator/xsd/xsdcontainer.xml';
        self::assertFileExists($file);

        $document = new DOMDocument();
        $document->load($file);

        return $document;
    }
}
