<?php

use App\Shared\Infrastructure\Config\AppConfig;
use App\User\Interface\Controller\AuthenticateUserController;
use App\User\Interface\Controller\RegisterUserController;
use App\Video\Interface\Controller\UploadUserVideoController;

Route::get('/version', static fn (AppConfig $appConfig) => response()->json(['version' => $appConfig->getVersion()]));

Route::prefix('users')->name('users.')->group(function () {
    Route::post('register', RegisterUserController::class);
    Route::post('authenticate', AuthenticateUserController::class);
});

Route::prefix('videos')->name('videos')->group(function () {
    Route::post('/', UploadUserVideoController::class);
});