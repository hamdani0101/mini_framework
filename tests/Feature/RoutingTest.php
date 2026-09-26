<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Core\Config;
use App\Core\Router;
use App\Core\View;
use PHPUnit\Framework\TestCase;

final class RoutingTest extends TestCase
{
    protected function setUp(): void
    {
        Config::reset();
        View::reset();
        Config::load(base_path('config'));
    }

    public function testHealthRouteReturnsJson(): void
    {
        $router = new Router();
        $router->get('/health', [\App\Controllers\HomeController::class, 'health']);

        ob_start();
        $router->dispatch('GET', '/health');
        $output = (string) ob_get_clean();

        $this->assertJson($output);
        $this->assertSame(['status' => 'ok'], json_decode($output, true));
    }

    public function testUnknownRouteReturns404(): void
    {
        $router = new Router();
        $router->get('/', [\App\Controllers\HomeController::class, 'index']);

        http_response_code(200);
        ob_start();
        $router->dispatch('GET', '/nope-' . uniqid());
        ob_end_clean();

        $this->assertSame(404, http_response_code());
    }
}
