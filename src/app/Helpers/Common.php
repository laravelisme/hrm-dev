<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('activeState')) {
    function activeState($route, $output = "active")
    {
        return Route::currentRouteNamed($route) ? $output : '';
    }
}
