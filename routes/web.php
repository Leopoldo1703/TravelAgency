<?php

use Illuminate\Support\Facades\Route;
use Lightit\Shared\App\Exceptions\InvalidActionException;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/cities', function () {
    return view('cities');
});

Route::get('/airlines', function () {
    return view('airlines');
});

Route::get('invalid', static fn() => throw new InvalidActionException("Is not valid"));

Route::get('{unknown}', static fn () => view('app  '))->where('unknown', '^(?!api).*$');

