<?php

declare(strict_types=1);

namespace NunoMaduro\PhpInsights\Application\Adapters\Symfony;

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
        return 'symfony';
    }

    /**
     * {@inheritDoc}
     */
    public static function get(): array
    {
        return [
            'exclude' => [
                'var',
                'translations',
                'config',
                'public',
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
    public static function shouldBeApplied(array $composer, string $directory): bool
    {
        /** @var string[] $requirements */
        $requirements = $composer['require'] ?? [];

        foreach (array_keys($requirements) as $requirement) {
            $requirement = (string) $requirement;

            if (strpos($requirement, 'symfony/framework-bundle') !== false
                || strpos($requirement, 'symfony/flex') !== false
                || strpos($requirement, 'symfony/symfony') !== false) {
                return true;
            }
        }

        return false;
    }
}
