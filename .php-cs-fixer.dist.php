<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/sample',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->exclude([
        'runtime',
        '_runtime',
        'web/assets',
    ])
    ->notPath();

$config = new PhpCsFixer\Config();

return $config
    ->setFinder($finder)
    ->setRules([
        '@PSR12' => true,
        'function_declaration' => false,
        'new_with_braces' => [
            'anonymous_class' => false,
        ],
    ]);
