<?php


$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src/')
    ->in(__DIR__ . '/tests/')
    ->exclude('Fixtures/')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    'array_syntax' => ['syntax' => 'short'],
    'ordered_class_elements' => true,
    'php_unit_internal_class' => false,
    'php_unit_test_class_requires_covers' => false,
    'phpdoc_add_missing_param_annotation' => ['only_untyped' => false]
])
    ->setFinder($finder)
    ;
