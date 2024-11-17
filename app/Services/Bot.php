<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CategoryEnum;
use App\Models\Recipe;
use App\Models\TelegramUser;

final class Bot
{
    private TelegramUser $telegramUser;

    public function __construct(private readonly Telegram $telegram)
    {
        /** @var TelegramUser|null $telegramUser */
        $telegramUser = TelegramUser::query()->where('telegram_id', $this->telegram->userId)->first();

        if ($telegramUser === null) {
            $telegramUser = new TelegramUser;
            $telegramUser->telegram_id = $this->telegram->userId;
            $telegramUser->save();
        }

        $this->telegramUser = $telegramUser;
    }

    public function run(): void
    {
        if (! $this->telegram->isPrivate) {
            return;
        }

        $flows = [
            '/start' => 'start',
            '/new' => 'new',
            '/recipeCategory' => 'recipeCategory',
            '/search' => 'search',
            '/none' => 'none',
        ];

        $method = $flows['/none'];

        if (isset($flows[$this->telegram->command])) {
            $method = $flows[$this->telegram->command];
        } elseif (isset($flows[$this->telegram->message])) {
            $method = $flows[$this->telegram->message];
        } elseif ($this->telegramUser->state) {
            $method = $flows[$this->telegramUser->state];
        }

        $this->$method();
    }

    private function start(): void
    {
        $this->telegram->replyMessage('Telegram-бот для хранения рецептов');
    }

    private function new(): void
    {
        if ($this->telegramUser->state === '/new') {
            $recipe = new Recipe;

            $message = trim($this->telegram->message);

            if (filter_var($message, FILTER_VALIDATE_URL) !== false) {
                $recipe->title = $this->getTitleFromUrl($message);
                $recipe->link = $message;
            } else {
                preg_match('~\A(.+)~', $message, $match);

                $recipe->title = $match[1];
                $recipe->text = $message;
            }

            $recipe->category = CategoryEnum::NONE;
            $recipe->save();

            $this->telegramUser->state = null;
            $this->telegramUser->save();

            $categories = CategoryEnum::labels();

            $buttons  = [];

            foreach ($categories as $categoryId => $categoryName) {
                $buttons[] =  ['text' => $categoryName, 'callback_data' => '/recipeCategory_'.$recipe->id.'_'.$categoryId];
            }

            $this->telegram->replyMessageWithInlineButtons('Рецепт сохранен, выберите категорию', [$buttons]);

            return;
        }

        $this->telegram->replyMessage('Пришлите ссылку или текст рецепта');

        $this->telegramUser->state = '/new';
        $this->telegramUser->save();
    }

    private function recipeCategory(): void
    {
        $recipeId = $this->telegram->commandPostfixes[0];
        $category = $this->telegram->commandPostfixes[1];

        Recipe::query()->where('id', $recipeId)->update(['category' => $category]);

        $this->telegram->replyMessage('Категория сохранена');
    }

    private function none(): void
    {
        $this->telegram->replyMessage('Неизвестная команда');
    }

    private function getTitleFromUrl(string $url): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36');

        $htmlContent = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($htmlContent === false) {
            logger()?->error("Ошибка загрузки страницы: $error");

            return 'Без заголовка';
        }

        if (preg_match('~<title>(.*?)</title>~si', $htmlContent, $matches)) {
            return trim($matches[1]); // Возвращаем текст из тега <title>
        }

        return 'Без заголовка';
    }
}
