<?php

return (new \PhpCsFixer\Config())
    ->setFinder(
        \Symfony\Component\Finder\Finder::create()
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/tests',
                __DIR__ . '/build',
                __DIR__ . '/tools',
            ])
            ->name('*.php')
    )
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'align_multiline_comment' => true,
        'array_indentation' => true,
        'declare_strict_types' => true,
        'final_class' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'list_syntax' => [
            'syntax' => 'short',
        ],
        'constant_case' => [
            'case' => 'lower',
        ],
        'multiline_comment_opening_closing' => true,
        'native_function_casing' => true,
        'no_empty_phpdoc' => true,
        'no_leading_import_slash' => true,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
        ],
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => [
            'imports_order' => ['class', 'function', 'const'],
        ],
        'ordered_interfaces' => true,
        'php_unit_test_annotation' => true,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'static',
        ],
        'php_unit_method_casing' => [
            'case' => 'snake_case',
        ],
        'single_import_per_statement' => true,
        'single_trait_insert_per_statement' => true,
        'static_lambda' => true,
        'strict_comparison' => true,
        'strict_param' => true,
    ])
;
