<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Helper;

trait TmpFileTrait
{
    /**
     * @param callable(string): void
     */
    private function createTmpFile(callable $run): void
    {
        $path = tempnam(sys_get_temp_dir(), 'veewee-xml');

        try {
            $run($path);
        } finally {
            @unlink($path);
        }
    }
}
