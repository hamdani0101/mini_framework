<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    protected function setUp(): void
    {
        Config::reset();
    }

    public function testGetReturnsDefaultForMissingKey(): void
    {
        $this->assertSame('fallback', Config::get('missing.key', 'fallback'));
    }

    public function testLoadReadsPhpFiles(): void
    {
        $dir = sys_get_temp_dir() . '/mf-config-' . uniqid();
        mkdir($dir);
        file_put_contents($dir . '/app.php', "<?php return ['name' => 'Test App'];");

        Config::load($dir);

        $this->assertSame('Test App', Config::get('app.name'));
    }
}
