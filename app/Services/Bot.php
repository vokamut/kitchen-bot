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

        $category = preg_replace('~ \(\d+\)$~', '', $this->telegram->message);

        if (in_array($category, CategoryEnum::labels(), true)) {
            $this->searchRecipeByCategory(CategoryEnum::getCaseByLabel($category));

            $this->telegramUser->state = null;
            $this->telegramUser->save();

            return;
        }

        $flows = [
            '/start' => 'start',
            '/delete' => 'delete',
            '/deleteNo' => 'deleteNo',
            '/deleteYes' => 'deleteYes',
            'Назад' => 'back',
            'Добавить' => 'new',
            '/new' => 'new',
            '/recipeCategory' => 'recipeCategory',
            '/backRecipeCategory' => 'backRecipeCategory',
            'Поиск' => 'search',
            '/search' => 'search',
            '/r' => 'r',
            '/none' => 'none',
        ];

        $method = $flows['/none'];

        if (isset($flows[$this->telegram->command])) {
            $method = $flows[$this->telegram->command];

            $this->telegramUser->state = null;
            $this->telegramUser->save();
        } elseif (isset($flows[$this->telegram->message])) {
            $method = $flows[$this->telegram->message];

            $this->telegramUser->state = null;
            $this->telegramUser->save();
        } elseif ($this->telegramUser->state) {
            $method = $this->telegramUser->state;
        }

        $this->$method();
    }

    private function start(): void
    {
        $this->telegram->replyMessageWithButtons(
            'Telegram-бот для хранения рецептов',
            [
                ['Добавить', 'Поиск'],
            ]
        );

        $this->telegramUser->state = null;
        $this->telegramUser->save();
    }

    private function back(): void
    {
        $this->telegram->replyMessageWithButtons(
            'Выберите действие',
            [
                ['Добавить', 'Поиск'],
            ]
        );

        $this->telegramUser->state = null;
        $this->telegramUser->save();
    }

    private function new(): void
    {
        if ($this->telegramUser->state === 'new') {
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

            $this->telegram->replyMessageWithInlineButtons(
                'Рецепт сохранен, выберите категорию',
                $this->getButtonsByCategories('/recipeCategory', $recipe->id)
            );

            return;
        }

        $this->telegram->replyMessage('Пришлите ссылку или текст рецепта');

        $this->telegramUser->state = 'new';
        $this->telegramUser->save();
    }

    private function backRecipeCategory(): void
    {
        $recipeId = (int) $this->telegram->commandPostfixes[0];

        $this->telegram->editMessageTextWithInlineButtons(
            $this->telegram->chatId,
            $this->telegram->messageId,
            'Рецепт сохранен, выберите категорию',
            $this->getButtonsByCategories('/recipeCategory', $recipeId)
        );
    }

    private function recipeCategory(): void
    {
        $recipeId = (int) $this->telegram->commandPostfixes[0];
        $category = (int) $this->telegram->commandPostfixes[1];

        if (array_key_exists($category, CategoryEnum::parentLabels())) {
            $this->telegram->editMessageTextWithInlineButtons(
                $this->telegram->chatId,
                $this->telegram->messageId,
                'Рецепт сохранен, выберите категорию',
                $this->getButtonsByCategories('/recipeCategory', $recipeId, $category)
            );

            return;
        }

        $recipe = Recipe::query()->where('id', $recipeId)->first();

        if ($recipe === null) {
            $this->telegram->deleteMessage($this->telegram->chatId, $this->telegram->messageId);

            return;
        }

        $recipe->category = CategoryEnum::getCaseByValue($category);
        $recipe->save();

        $this->telegram->deleteMessage($this->telegram->chatId, $this->telegram->messageId);
        $this->telegram->replyMessage('Рецепт "'.$recipe->title.'" помещен в категорию "'.CategoryEnum::getLabelByCase($recipe->category).'"');

        $this->telegramUser->state = null;
        $this->telegramUser->save();
    }

    private function delete(): void
    {
        $recipeId = (int) $this->telegram->commandPostfixes[0];

        $this->telegram->editMessageTextWithInlineButtons(
            $this->telegram->chatId,
            $this->telegram->messageId,
            $this->telegram->request['callback_query']['message']['text'],
            [[
                ['text' => 'Нет', 'callback_data' => '/deleteNo_'.$recipeId],
                ['text' => 'Удалить', 'callback_data' => '/deleteYes_'.$recipeId],
            ]],
        );
    }

    private function deleteNo(): void
    {
        $recipeId = (int) $this->telegram->commandPostfixes[0];

        $this->telegram->editMessageTextWithInlineButtons(
            $this->telegram->chatId,
            $this->telegram->messageId,
            $this->telegram->request['callback_query']['message']['text'],
            [[['text' => 'Удалить', 'callback_data' => '/delete_'.$recipeId]]],
        );
    }

    private function deleteYes(): void
    {
        $recipeId = (int) $this->telegram->commandPostfixes[0];

        Recipe::query()->where('id', $recipeId)->delete();

        $this->telegram->deleteMessage($this->telegram->chatId, $this->telegram->messageId);
        $this->telegram->replyMessage('Рецепт удален');

        $this->telegramUser->state = null;
        $this->telegramUser->save();
    }

    private function none(): void
    {
        $this->telegram->replyMessage('Неизвестная команда');

        $this->telegramUser->state = null;
        $this->telegramUser->save();
    }

    private function search(): void
    {
        if ($this->telegramUser->state === 'search') {
            $recipes = Recipe::query()
                ->where('title', 'LIKE', '%'.$this->telegram->message.'%')
                ->orWhere('text', 'LIKE', '%'.$this->telegram->message.'%')
                ->orderBy('title')
                ->get();

            if ($recipes->isEmpty()) {
                $this->telegram->replyMessage('Рецепты не найдены');

                return;
            }

            $text = 'Выберите рецепт:'.PHP_EOL.PHP_EOL;

            foreach ($recipes as $recipe) {
                $text .= '/r_'.$recipe->id.' '.$recipe->title.PHP_EOL;
            }

            $this->telegram->replyMessage($text);

            return;
        }

        $category = null;
        if (is_array($this->telegram->commandPostfixes) && array_key_exists(0, $this->telegram->commandPostfixes)) {
            $category = (int) $this->telegram->commandPostfixes[0];
        }

        $this->telegram->replyMessageWithButtons(
            'Выберите категорию или напишите что искать',
            $this->getKeyboardByCategories($category)
        );

        $this->telegramUser->state = 'search';
        $this->telegramUser->save();
    }

    private function searchRecipeByCategory(CategoryEnum $category): void
    {
        $recipes = Recipe::query()
            ->select('id', 'title')
            ->where('category', $category->value)
            ->orderBy('title')
            ->get();

        if ($recipes->isEmpty()) {
            $this->telegram->replyMessage('В этой категории нет рецептов');

            return;
        }

        $text = 'Выберите рецепт:'.PHP_EOL.PHP_EOL;

        foreach ($recipes as $recipe) {
            $text .= '/r_'.$recipe->id.' '.$recipe->title.PHP_EOL;
        }

        $this->telegram->replyMessage($text);

        $this->telegramUser->state = null;
        $this->telegramUser->save();
    }

    private function r(): void
    {
        $recipeId = (int) $this->telegram->commandPostfixes[0];

        $recipe = Recipe::query()
            ->where('id', $recipeId)
            ->orderBy('title')
            ->first();

        if ($recipe === null) {
            $this->telegram->replyMessage('Нет такого рецепта');

            $this->telegramUser->state = null;
            $this->telegramUser->save();

            return;
        }

        $this->telegram->replyMessageWithInlineButtons(
            'Рецепт: '.$recipe->title.PHP_EOL.PHP_EOL.$recipe->text.$recipe->link,
            [[['text' => 'Удалить', 'callback_data' => '/delete_'.$recipe->id]]],
            false
        );

        $this->telegramUser->state = null;
        $this->telegramUser->save();
    }

    private function getKeyboardByCategories(?int $category = null): array
    {
        $categories = CategoryEnum::labels($category);

        $buttons = [];

        $row = 0;
        foreach ($categories as $categoryId => $categoryName) {
            $count = Recipe::query()->select('id')->where('category', $categoryId)->count();

            $buttons[$row][] = $categoryName.' ('.$count.')';

            if (count($buttons[$row]) === 3) {
                $row++;
            }
        }

        $buttons[$row + 1] = [
            'Назад',
        ];

        return $buttons;
    }

    private function getButtonsByCategories(string $command, ?int $recipeId = null, ?int $category = null): array
    {
        $categories = CategoryEnum::labels($category);

        $buttons = [];

        $row = 0;
        foreach ($categories as $categoryId => $categoryName) {
            $buttons[$row][] = ['text' => $categoryName, 'callback_data' => $command.'_'.$recipeId.'_'.$categoryId];

            if (count($buttons[$row]) === 3) {
                $row++;
            }
        }

        if ($category !== null) {
            $buttons[$row + 1][] = ['text' => 'Назад', 'callback_data' => '/backRecipeCategory_'.$recipeId];
        }

        return $buttons;
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
