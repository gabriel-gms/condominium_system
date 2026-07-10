<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BilletController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FoundAndLostController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WallController;
use App\Http\Controllers\WarningController;

Route::get("/ping", function (){
    return [ "pong" => true ];
});

Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/auth/validate', [AuthController::class, 'validateToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/walls', [WallController::class, 'getAll']);
    Route::post('/walls/{id}/like', [WallController::class, 'like']);

    Route::get('/documents', [DocumentController::class, 'getAll']);

    Route::get('/warnings', [WarningController::class, 'getMyWarnings']);
    Route::post('/warnings', [WarningController::class, 'setWarning']);
    Route::post('/warnings/file', [WarningController::class, 'setWarningFile']);

    Route::get('/billets', [BilletController::class, 'getMyBillets']);

    Route::get('/found-and-lost', [FoundAndLostController::class, 'getAll']);
    Route::post('/found-and-lost', [FoundAndLostController::class, 'setFoundAndLost']);
    Route::put('/found-and-lost/{id}', [FoundAndLostController::class, 'updateFoundAndLost']);

    Route::get('/units/{id}', [UnitController::class, 'getByIdUnit']);
    Route::post('/units/{param}/addPerson', [UnitController::class, 'createPersonInUnit']);
    Route::post('/units/{param}/addPet', [UnitController::class, 'createPetInUnit']);
    Route::post('/units/{param}/addVehicle', [UnitController::class, 'createVehicleInUnit']);
    Route::delete('/units/{param}/addPerson', [UnitController::class, 'removePersonInUnit']);
    Route::post('/units/{param}/addPet', [UnitController::class, 'removePetInUnit']);
    Route::post('/units/{param}/addVehicle', [UnitController::class, 'removeVehicleInUnit']);

    Route::get('/reservations', [ReservationController::class, 'getResevations']);
    Route::get('/myreservations', [ReservationController::class, 'getMyResevations']);
    Route::delete('/myreservations', [ReservationController::class, 'deleteMyResevations']);
    Route::post('/reservations/{param}', [ReservationController::class, 'createResevation']);
    Route::get('/reservations/{param}/disableddates', [ReservationController::class, 'getDisabledDates']);
    Route::get('/reservations/{param}/times', [ReservationController::class, 'times']);
});