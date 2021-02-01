<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xsl;


use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

final class XsltProcessor
{
    private \XSLTProcessor $processor;

    private function __construct(\XSLTProcessor $processor)
    {
        $this->processor = $processor;
    }
    
    public static function fromTemplateDocument(Document $template, callable ... $configurators): self
    {
        $proc = new \XSLTProcessor();

        disallow_libxml_false_returns(
            $proc->importStyleSheet($template->toUnsafeDocument()),
            'Unable to import XSLT stylesheet'
        );

        return new self($proc);
    }


    public function transform(): T
    {

    }

    public function transformToString(Document $document): string
    {

    }
}
