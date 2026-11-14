<?php

namespace App\Controllers;

use JiJiHoHoCoCo\IchiRoute\Controller\BaseController;

class PageController extends BaseController
{
    public function index()
    {
        $isDevelopment = gete('APP_ENV') === 'development';

        $serverUrl = gete('SERVER_URL');
        $vitePort = gete('VITE_PORT');

        $viteBaseUrl = rtrim(
            $serverUrl && $vitePort
                ? "{$serverUrl}:{$vitePort}"
                : 'http://localhost:5173',
            '/'
        );

        return view('index', [
            'isDevelopment' => $isDevelopment,
            'viteBaseUrl' => $viteBaseUrl,
        ]);
    }
}
