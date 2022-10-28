#!/usr/bin/env php
<?php

use Psl\File\WriteMode;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use function Psl\File\write;
use function Psl\Fun\pipe;
use function Psl\Str\join;
use function Psl\Vec\concat;
use function Psl\Vec\map;

(static function (): void {
    $root = dirname(__DIR__);
    require $root.'/vendor/autoload.php';
    $src = $root.'/src';
    $target = $src.'/bootstrap.php';

    // Clear file first!
    write($target, '', WriteMode::TRUNCATE);

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

    write($target, $build($files), WriteMode::TRUNCATE);

    echo 'Created bootstrap file!'.PHP_EOL;
})();
