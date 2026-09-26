<?php

declare(strict_types=1);

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Twig view renderer. Views live in views/, e.g. view('pages/home', [...]).
 */
final class View
{
    private static ?Environment $twig = null;

    public static function render(string $template, array $data = []): string
    {
        return self::twig()->render(self::normalize($template), $data);
    }

    private static function twig(): Environment
    {
        if (self::$twig instanceof Environment) {
            return self::$twig;
        }

        $loader = new FilesystemLoader(base_path('views'));
        $twig = new Environment($loader, [
            'cache' => Config::get('app.env') === 'local' ? false : base_path('storage/cache/twig'),
            'auto_reload' => true,
        ]);

        self::$twig = $twig;
        return $twig;
    }

    private static function normalize(string $template): string
    {
        // Allow 'pages/home' or 'pages/home.twig'.
        return str_ends_with($template, '.twig') ? $template : $template . '.twig';
    }

    /** @internal for tests */
    public static function reset(): void
    {
        self::$twig = null;
    }
}
