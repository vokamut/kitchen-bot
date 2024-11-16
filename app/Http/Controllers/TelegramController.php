<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\TelegramRequest;
use App\Services\Bot;
use App\Services\Telegram;
use Illuminate\Http\JsonResponse;

final class TelegramController extends Controller
{
    public function __invoke(TelegramRequest $request): JsonResponse
    {
        (new Bot(new Telegram))->run();

        return response()->json(['status' => 'ok']);
    }
}
