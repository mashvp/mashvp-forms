<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
  ->withPaths([
    __DIR__ . '/includes',
  ])
  ->withPhpVersion(PhpVersion::PHP_81)
  ->withPhpSets(php81: true)
  ->withPreparedSets(
    deadCode: true,
    codeQuality: true,
    codingStyle: true,
    typeDeclarations: true,
    rectorPreset: true,
  )
  ->withImportNames(
    importShortClasses: false,
    removeUnusedImports: true,
  );
