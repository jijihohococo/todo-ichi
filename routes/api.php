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
            $this->post('/todos', 'App\Controllers\API\TodoController@create');
            $this->put('/todos/{id}', 'App\Controllers\API\TodoController@update');
            $this->delete('/todos/{id}', 'App\Controllers\API\TodoController@delete');
        }
    );
}
