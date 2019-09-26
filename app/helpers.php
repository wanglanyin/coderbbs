<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function route_active($route,$id = null) {
    //return request()->fullUrl();
    if(!$id) {
        return request()->fullUrlIs(route($route));
    }
    return request()->fullUrlIs(route($route,$id));
}
