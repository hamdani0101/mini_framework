<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Env;
use PHPUnit\Framework\TestCase;

final class EnvTest extends TestCase
{
    public function testLoadParsesKeyValueFile(): void
    {
        $file = sys_get_temp_dir() . '/mf-env-' . uniqid() . '.env';
        file_put_contents($file, "MF_TEST_KEY=hello\n# comment\nMF_TEST_QUOTED=\"a b\"\n");

        Env::load($file);

        $this->assertSame('hello', $_ENV['MF_TEST_KEY'] ?? null);
        $this->assertSame('a b', $_ENV['MF_TEST_QUOTED'] ?? null);
    }

    public function testLoadDoesNotOverrideRealEnvironment(): void
    {
        $_ENV['MF_KEEP'] = 'original';
        $file = sys_get_temp_dir() . '/mf-env-' . uniqid() . '.env';
        file_put_contents($file, "MF_KEEP=changed\n");

        Env::load($file);

        $this->assertSame('original', $_ENV['MF_KEEP']);
        unset($_ENV['MF_KEEP']);
    }
}
