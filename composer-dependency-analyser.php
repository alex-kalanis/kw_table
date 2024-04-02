<?php

/**
 * Dependency analyzer configuration
 * @link https://github.com/shipmonk-rnd/composer-dependency-analyser
 */

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    // ignore errors on specific packages and paths
    ->ignoreErrorsOnPackageAndPath('alex-kalanis/kw_clipr', __DIR__ . '/php-src/output_cli/CliRenderer.php', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackageAndPath('nette/forms', __DIR__ . '/php-src/form_nette', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackageAndPath('latte/latte', __DIR__ . '/php-src/output_latte', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackageAndPath('twig/twig', __DIR__ . '/php-src/output_twig', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreUnknownClasses(['Nette\Application\UI\Form'])
    ->ignoreErrorsOnPath(__DIR__ . '/php-src/form_nette', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPath(__DIR__ . '/php-src/nette', [ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnPath(__DIR__ . '/php-src/output_blade', [ErrorType::SHADOW_DEPENDENCY])
;
