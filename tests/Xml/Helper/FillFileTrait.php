<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Helper;

trait FillFileTrait
{
    /**
     * @return array{string, resource}
     */
    private function fillFile(string $content): array
    {
        $handle = tmpfile();
        $path = stream_get_meta_data($handle)['uri'];
        fwrite($handle, $content);

        return [$path, $handle];
    }
}
