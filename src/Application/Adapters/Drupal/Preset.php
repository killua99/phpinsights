<?php

declare(strict_types=1);

namespace NunoMaduro\PhpInsights\Application\Adapters\Drupal;

use NunoMaduro\PhpInsights\Domain\Contracts\Preset as PresetContract;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff;

/**
 * @internal
 */
final class Preset implements PresetContract
{
    /**
     * {@inheritDoc}
     */
    public static function getName(): string
    {
        return 'drupal';
    }

    /**
     * {@inheritDoc}
     */
    public static function get(): array
    {
        return [
            'exclude' => [
                'core',
                'modules/contrib',
                'sites',
                'profiles/contrib',
                'themes/contrib',
            ],
            'config' => [
                ForbiddenFunctionsSniff::class => [
                    'forbiddenFunctions' => [
                        'dd' => null,
                        'dump' => null,
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function shouldBeApplied(array $composer): bool
    {
        /** @var array<string, string> $requirements */
        $requirements = $composer['require'] ?? [];

        /** @var array<string, string> $replace */
        $replace = $composer['replace'] ?? [];

        foreach (array_keys(array_merge($requirements, $replace)) as $requirement) {
            if (strpos($requirement, 'drupal/core') !== false) {
                return true;
            }
        }

        return false;
    }
}
