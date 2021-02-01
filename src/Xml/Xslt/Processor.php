<?php

declare(strict_types=1);

namespace VeeWee\Xml\XslT;

use VeeWee\Xml\Dom\Document;
use XSLTProcessor;
use function Psl\Fun\pipe;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

final class Processor
{
    private XSLTProcessor $processor;

    private function __construct(XSLTProcessor $processor)
    {
        $this->processor = $processor;
    }
    
    public static function fromTemplateDocument(Document $template, callable ... $configurators): self
    {
        $proc = pipe(...$configurators)(new XSLTProcessor());

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
