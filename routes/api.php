<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::any('telegram', TelegramController::class);
