<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Example landing controller proving the wiring works.
 */
class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('pages/home', [
            'appName' => config('app.name', 'Mini Framework'),
            'env' => config('app.env', 'local'),
        ]);
    }

    public function health(): void
    {
        $this->json(['status' => 'ok']);
    }
}
