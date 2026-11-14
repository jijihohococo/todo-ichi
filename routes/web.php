<?php

use JiJiHoHoCoCo\IchiRoute\Router\Route;

function web(Route $route): void
{
    $route->get('/', 'PageController@index');
}
