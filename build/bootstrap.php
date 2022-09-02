#!/usr/bin/env php
<?php

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use function Psl\Arr\concat;
use function Psl\Arr\map;
use function Psl\Fun\pipe;
use function Psl\Str\join;

(static function (): void {
    $root = dirname(__DIR__);
    require $root.'/vendor/autoload.php';
    $src = $root.'/src';

    $files = Finder::create()
        ->in(dirname(__DIR__).'/src')
        ->files()
        ->name('*.php')
        ->notName('bootstrap.php')
        ->notContains(['/^final class/m', '/^abstract class/m', '/^interface/m'])
        ->sortByName()
        ->getIterator();

    /** @var callable(list<SplFileInfo>):string $build */
    $build = pipe(
        static fn (iterable $files): iterable => map(
            $files,
            static fn (SplFileInfo $file): string => 'require_once __DIR__.\'/'.$file->getRelativePathname().'\';'
        ),
        static fn (iterable $codeLines): iterable => concat(['<?php declare(strict_types=1);', ''], $codeLines),
        static fn (iterable $codeLines): iterable => concat($codeLines, ['']),
        static fn (iterable $codeLines): string => join($codeLines, PHP_EOL)
    );

    file_put_contents($src.'/bootstrap.php', $build($files));

    echo 'Created bootstrap file!'.PHP_EOL;
})();
