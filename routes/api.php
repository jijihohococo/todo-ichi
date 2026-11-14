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
            $this->get('/todos', 'API\TodoController@index');
            $this->get('/todos/{id}', 'API\TodoController@show');
            $this->post('/todos', 'API\TodoController@create');
            $this->put('/todos/{id}', 'API\TodoController@update');
            $this->delete('/todos/{id}', 'API\TodoController@delete');
        }
    );
}
