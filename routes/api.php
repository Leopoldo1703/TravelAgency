<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lightit\Backoffice\Airlines\App\Controllers\DeleteAirlineController;
use Lightit\Backoffice\Airlines\App\Controllers\GetAirlineController;
use Lightit\Backoffice\Airlines\App\Controllers\ListAirlinesController;
use Lightit\Backoffice\Airlines\App\Controllers\ListCitiesFromAirlineController;
use Lightit\Backoffice\Airlines\App\Controllers\StoreAirlineController;
use Lightit\Backoffice\Airlines\App\Controllers\UpdateAirlineController;
use Lightit\Backoffice\Cities\App\Controllers\DeleteCityController;
use Lightit\Backoffice\Cities\App\Controllers\GetCityController;
use Lightit\Backoffice\Cities\App\Controllers\ListCitiesController;
use Lightit\Backoffice\Cities\App\Controllers\StoreCityController;
use Lightit\Backoffice\Cities\App\Controllers\UpdateCityController;
use Lightit\Backoffice\Flights\App\Controllers\DeleteFlightController;
use Lightit\Backoffice\Flights\App\Controllers\GetFlightController;
use Lightit\Backoffice\Flights\App\Controllers\ListFlightsController;
use Lightit\Backoffice\Flights\App\Controllers\StoreFlightController;
use Lightit\Backoffice\Flights\App\Controllers\UpdateFlightController;
use Lightit\Backoffice\Users\App\Controllers\DeleteUserController;
use Lightit\Backoffice\Users\App\Controllers\GetUserController;
use Lightit\Backoffice\Users\App\Controllers\ListUserController;
use Lightit\Backoffice\Users\App\Controllers\StoreUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
*/
Route::prefix('users')
    ->middleware([])
    ->group(static function () {
        Route::get('/', ListUserController::class);
        Route::get('/{user}', GetUserController::class)->withTrashed();
        Route::post('/', StoreUserController::class);
        Route::delete('/{user}', DeleteUserController::class);
    });

Route::prefix('cities')
    ->middleware([])
    ->group(static function () {
        Route::post('/', StoreCityController::class)->name('cities');
        Route::get('/', ListCitiesController::class);
        Route::prefix('{city}')->group(function () {
            Route::get('/', GetCityController::class);
            Route::patch('/', UpdateCityController::class);
            Route::delete('/', DeleteCityController::class);
        });
    });

Route::prefix('airlines')
    ->middleware([])
    ->group(static function () {
        Route::get('/', ListAirlinesController::class);
        Route::post('/', StoreAirlineController::class);
        Route::prefix('{airline}')->group(function () {
            Route::get('/', GetAirlineController::class);
            Route::put('/', UpdateAirlineController::class);
            Route::delete('/', DeleteAirlineController::class);
            Route::get('/cities', ListCitiesFromAirlineController::class);
        });
    });

Route::prefix('flights')
    ->middleware([])
    ->group(static function () {
        Route::post('/', StoreFlightController::class)->name('flights');
        Route::get('/', ListFlightsController::class);
        Route::prefix('{flight}')->group(function () {
            Route::get('/', ListFlightsController::class);
            Route::put('/', UpdateFlightController::class);
            Route::delete('/', DeleteFlightController::class);
        });
    });