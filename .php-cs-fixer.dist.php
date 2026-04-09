<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
  ->setRiskyAllowed(false)
  ->setIndent('  ')
  ->setRules([
    '@auto' => true,
    '@PSR12' => true,
    'array_indentation' => true,
    'indentation_type' => true,
    'binary_operator_spaces' => [
      'default' => 'single_space',
    ],
    'method_argument_space' => [
      'on_multiline' => 'ensure_fully_multiline',
    ],
    'trailing_comma_in_multiline' => [
      'elements' => [
        'arrays',
        'arguments',
        'parameters'
      ],
    ],
  ])

  ->setFinder(
    (new Finder())->in(__DIR__ . '/includes')
  )
;
