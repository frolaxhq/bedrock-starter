<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\QrCode\Http\Controllers\QrCodeController;

Route::middleware('web')->prefix('qr-code')->name('qr-code.')->group(function (): void {
    Route::get('/', [QrCodeController::class, 'index'])->name('index');
    Route::get('/svg', [QrCodeController::class, 'svg'])->name('svg');
});
