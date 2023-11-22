<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSemicolonToColonFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestClassRequiresCoversFixer;
use PhpCsFixer\Fixer\Semicolon\MultilineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/app/booking/src',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $services = $containerConfigurator->services();
    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [[
            'syntax' => 'short',
        ]]);

    $services->set(MethodArgumentSpaceFixer::class)
        ->call('configure', [[
            'on_multiline' => 'ensure_fully_multiline',
        ]]);

    $services->set(NativeFunctionInvocationFixer::class)
        ->call('configure', [[
            'scope' => 'namespaced',
            'include' => ['@compiler_optimized'],
        ]]);

    $services->set(DeclareStrictTypesFixer::class);
    $services->set(ConcatSpaceFixer::class)->call('configure', [['spacing' => 'one']]);
    $services->set(MultilineWhitespaceBeforeSemicolonsFixer::class);
    $services->remove(YodaStyleFixer::class);
    $services->remove(PhpUnitTestClassRequiresCoversFixer::class);
    $services->remove(SwitchCaseSemicolonToColonFixer::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::CACHE_DIRECTORY, '.ecs_cache');
    $parameters->set(Option::CACHE_NAMESPACE, 'my_project_namespace');


};
