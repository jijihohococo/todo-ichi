<?php

use JiJiHoHoCoCo\IchiRoute\Router\Route;

function api(Route $route): void
{
    $route->group(
        [
            'url_group' => 'api',
            'middleware' => 'JiJiHoHoCoCo\IchiRoute\Middleware\APIMiddleware'
        ],
        function () {
            $this->get('/todos', 'App\Controllers\API\TodoController@index');
            $this->get('/todos/{id}', 'App\Controllers\API\TodoController@show');
        }
    );
}
