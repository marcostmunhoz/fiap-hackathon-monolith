<?php

use App\Shared\Infrastructure\Config\AppConfig;
use App\User\Interface\Controller\AuthenticateUserController;
use App\User\Interface\Controller\RegisterUserController;

Route::get('/version', static fn (AppConfig $appConfig) => response()->json(['version' => $appConfig->getVersion()]));

Route::prefix('users')->name('users.')->group(function () {
    Route::post('register', RegisterUserController::class);
    Route::post('authenticate', AuthenticateUserController::class);
});