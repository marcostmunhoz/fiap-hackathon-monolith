<?php

use App\Shared\Infrastructure\Config\AppConfig;
use App\User\Interface\Controller\AuthenticateUserController;
use App\User\Interface\Controller\RegisterUserController;
use App\Video\Interface\Controller\DownloadUserVideoController;
use App\Video\Interface\Controller\ListUserVideosController;
use App\Video\Interface\Controller\UploadUserVideoController;

Route::get('/version', static fn (AppConfig $appConfig) => response()->json(['version' => $appConfig->getVersion()]));

Route::prefix('users')->name('users.')->group(function () {
    Route::post('register', RegisterUserController::class);
    Route::post('authenticate', AuthenticateUserController::class);
});

Route::prefix('videos')->name('videos')->middleware('authenticate.video-user')->group(function () {
    Route::post('/', UploadUserVideoController::class);
    Route::get('/', ListUserVideosController::class);
    Route::get('{id}/download', DownloadUserVideoController::class);
});