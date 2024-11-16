<?php

declare(strict_types=1);

namespace App\Services;

final class Telegram
{
    public array $request = [];

    public ?int $userId = null;

    public ?int $chatId = null;

    public ?string $username = null;

    public ?string $firstName = null;

    public ?string $lastName = null;

    public bool $isPrivate = false;

    public ?int $messageId = null;

    public string $fullMessage = '';

    public string $message = '';

    public ?array $document = null;

    public bool $isReply = false;

    public bool $isForward = false;

    public ?int $replyMessageId = null;

    public string $command = '';

    public ?array $commandPostfixes = null;

    public function __construct()
    {
        $this->request = (array) json_decode(file_get_contents('php://input'), true);

        if (array_key_exists('update_id', $this->request)) {
            $this->parseRequest();
        }
    }

    private function parseRequest(): void
    {
        logger()?->info('TG request: '.json_encode($this->request, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->isForward = isset($this->request['message']['forward_date']);

        if (array_key_exists('callback_query', $this->request)) {
            $this->userId = $this->request['callback_query']['from']['id'];
            $this->chatId = $this->request['callback_query']['message']['chat']['id'];
            $this->username = strtolower($this->request['callback_query']['from']['username'] ?? '');
            $this->firstName = $this->request['callback_query']['from']['first_name'] ?? '';
            $this->lastName = $this->request['callback_query']['from']['last_name'] ?? '';
            $this->isPrivate = $this->request['callback_query']['message']['chat']['id'] > 0;
            $this->messageId = $this->request['callback_query']['message']['message_id'];
            $this->fullMessage = $this->request['callback_query']['data'];
            $this->document = $this->request['callback_query']['document'] ?? null;
        } elseif (array_key_exists('edited_message', $this->request)) {
            $this->userId = $this->request['edited_message']['from']['id'];
            $this->chatId = $this->request['edited_message']['chat']['id'];
            $this->username = strtolower($this->request['edited_message']['from']['username'] ?? '');
            $this->firstName = $this->request['edited_message']['from']['first_name'] ?? '';
            $this->lastName = $this->request['edited_message']['from']['last_name'] ?? '';
            $this->isPrivate = $this->request['edited_message']['chat']['id'] > 0;
            $this->messageId = $this->request['edited_message']['message_id'];
            $this->fullMessage = $this->request['edited_message']['text'] ?? $this->request['edited_message']['caption'] ?? '';
            $this->document = $this->request['edited_message']['document'] ?? null;
            $this->isReply = array_key_exists('reply_to_message', $this->request['edited_message']);

            if ($this->isReply) {
                $this->replyMessageId = $this->request['edited_message']['reply_to_message']['message_id'];
            }
        } elseif (array_key_exists('my_chat_member', $this->request)) {
            $this->userId = $this->request['my_chat_member']['from']['id'];
            $this->chatId = $this->request['my_chat_member']['chat']['id'];
            $this->username = strtolower($this->request['my_chat_member']['from']['username'] ?? '');
            $this->firstName = $this->request['my_chat_member']['from']['first_name'] ?? '';
            $this->lastName = $this->request['my_chat_member']['from']['last_name'] ?? '';
            $this->isPrivate = $this->request['my_chat_member']['chat']['id'] > 0;
            $this->fullMessage = $this->request['my_chat_member']['text'] ?? $this->request['my_chat_member']['caption'] ?? '';
            $this->document = $this->request['my_chat_member']['document'] ?? null;
            $this->isReply = array_key_exists('reply_to_message', $this->request['my_chat_member']);
        } elseif (array_key_exists('message', $this->request)) {
            $this->userId = $this->request['message']['from']['id'];
            $this->chatId = $this->request['message']['chat']['id'];
            $this->username = strtolower($this->request['message']['from']['username'] ?? '');
            $this->firstName = $this->request['message']['from']['first_name'] ?? '';
            $this->lastName = $this->request['message']['from']['last_name'] ?? '';
            $this->isPrivate = $this->request['message']['chat']['id'] > 0;
            $this->messageId = $this->request['message']['message_id'];
            $this->fullMessage = $this->request['message']['text'] ?? $this->request['message']['caption'] ?? '';
            $this->document = $this->request['message']['document'] ?? null;
            $this->isReply = array_key_exists('reply_to_message', $this->request['message']);

            if ($this->isReply) {
                $this->replyMessageId = $this->request['message']['reply_to_message']['message_id'];
            }
        }

        preg_match('~^(/\w+)([_\d]*)@?.*~', $this->fullMessage, $matches);

        $this->message = (string) str_replace('@'.config('services.telegram.username'), '', $this->fullMessage);

        if (array_key_exists(1, $matches)) {
            $this->command = rtrim($matches[1], '_');

            $this->message = preg_replace('~^'.preg_quote($matches[1].$matches[2], '~').'\s*~', '', $this->message);
        }

        if (array_key_exists(2, $matches)) {
            $this->commandPostfixes = $matches[2] === '' ? null : explode('_', $matches[2]);
        }
    }

    public function sendRaw(
        string $method,
        string|array $data = [],
        array $headers = ['Content-Type: application/json'],
        bool $encodeToJson = true
    ): array {
        logger()?->info('Bot: '.$method.' '.json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $ch = curl_init('https://api.telegram.org/bot'.config('services.telegram.token').'/'.$method);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeToJson ? json_encode($data) : $data);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return [];
        }

        $response = json_decode($response, true);

        logger()?->info('TG: '.json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return (array) $response;
    }

    public function getMe(): array
    {
        return $this->sendRaw('getMe');
    }

    public function sendMessage(int $chatId, string $message, ?int $replyMessageId = null, array $entities = []): array
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $message,
            'entities' => $entities,
            'disable_web_page_preview' => true,
        ];

        if (empty($entities)) {
            $params['parse_mode'] = 'html';
        }

        if ($replyMessageId !== null) {
            $params['reply_to_message_id'] = $replyMessageId;
        }

        return $this->sendRaw('sendMessage', $params);
    }

    public function editMessageText(int $chatId, int $messageId, string $message): array
    {
        return $this->sendRaw('editMessageText', [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $message,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
        ]);
    }

    public function editMessageTextWithInlineButtons(int $chatId, int $messageId, string $message, array $buttons): array
    {
        return $this->sendRaw('editMessageText', [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $message,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
            'reply_markup' => [
                'inline_keyboard' => $buttons,
            ],
        ]);
    }

    public function editMessageCaption(int $chatId, int $messageId, string $caption): array
    {
        return $this->sendRaw('editMessageCaption', [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'caption' => $caption,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
        ]);
    }

    public function replyMessage(string $message, ?int $replyMessageId = null, array $entities = []): array
    {
        return $this->sendMessage($this->chatId, $message, $replyMessageId, $entities);
    }

    public function replyMessageWithInlineButtons(string $message, array $buttons): array
    {
        return $this->sendMessageWithInlineButtons($this->chatId, $message, $buttons);
    }

    public function replyMessageWithButtons(string $message, array $buttons): array
    {
        return $this->sendMessageWithButtons($this->chatId, $message, $buttons);
    }

    public function sendSticker(int $chatId, string $sticker): array
    {
        return $this->sendRaw('sendSticker', [
            'chat_id' => $chatId,
            'sticker' => $sticker,
        ]);
    }

    public function sendDocument(int $chatId, string $documentId, string $caption = ''): array
    {
        return $this->sendRaw('sendDocument', [
            'chat_id' => $chatId,
            'document' => $documentId,
            'caption' => $caption,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
        ]);
    }

    public function sendDocumentWithInlineButtons(int $chatId, string $documentId, string $caption, array $buttons): array
    {
        return $this->sendRaw('sendDocument', [
            'chat_id' => $chatId,
            'document' => $documentId,
            'caption' => $caption,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
            'reply_markup' => [
                'inline_keyboard' => $buttons,
            ],
        ]);
    }

    public function sendDocumentByPath(int $chatId, string $path, string $caption = ''): array
    {
        return $this->sendRaw('sendDocument', [
            'chat_id' => $chatId,
            'document' => curl_file_create($path, mime_content_type($path)),
            'caption' => $caption,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
        ], [
            'Content-Type: multipart/form-data',
        ], false);
    }

    public function sendDocumentByPathWithInlineButtons(int $chatId, string $path, string $caption, array $buttons): array
    {
        return $this->sendRaw('sendDocument', [
            'chat_id' => $chatId,
            'document' => curl_file_create($path, mime_content_type($path)),
            'caption' => $caption,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons,
            ]),
        ], [
            'Content-Type: multipart/form-data',
        ], false);
    }

    public function replyDocument(string $documentId, string $caption = ''): array
    {
        return $this->sendDocument($this->chatId, $documentId, $caption);
    }

    public function sendPhoto(int $chatId, string $photoId, string $caption = '', array $entities = []): array
    {
        $params = [
            'chat_id' => $chatId,
            'photo' => $photoId,
            'caption' => $caption,
            'caption_entities' => $entities,
            'disable_web_page_preview' => true,
        ];

        if (empty($entities)) {
            $params['parse_mode'] = 'html';
        }

        return $this->sendRaw('sendPhoto', $params);
    }

    public function replyPhoto(string $photoId, string $caption = '', array $entities = []): array
    {
        return $this->sendPhoto($this->chatId, $photoId, $caption, $entities);
    }

    public function sendPhotoWithInlineButtons(int $chatId, string $photoId, string $caption, array $buttons): array
    {
        return $this->sendRaw('sendPhoto', [
            'chat_id' => $chatId,
            'photo' => $photoId,
            'caption' => $caption,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
            'reply_markup' => [
                'inline_keyboard' => $buttons,
            ],
        ]);
    }

    public function replyPhotoWithInlineButtons(string $photoId, string $caption, array $buttons): array
    {
        return $this->sendPhotoWithInlineButtons($this->chatId, $photoId, $caption, $buttons);
    }

    public function sendVideo(int $chatId, string $videoId, string $caption = ''): array
    {
        return $this->sendRaw('sendVideo', [
            'chat_id' => $chatId,
            'video' => $videoId,
            'caption' => $caption,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
        ]);
    }

    public function replyVideo(string $videoId, string $caption = ''): array
    {
        return $this->sendVideo($this->chatId, $videoId, $caption);
    }

    public function sendVideoWithInlineButtons(int $chatId, string $videoId, string $caption, array $buttons): array
    {
        return $this->sendRaw('sendVideo', [
            'chat_id' => $chatId,
            'video' => $videoId,
            'caption' => $caption,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
            'reply_markup' => [
                'inline_keyboard' => $buttons,
            ],
        ]);
    }

    public function replyVideoWithInlineButtons(string $videoId, string $caption, array $buttons): array
    {
        return $this->sendVideoWithInlineButtons($this->chatId, $videoId, $caption, $buttons);
    }

    public function forwardMessage(int $fromChatId, int $messageId, int $toChatId): array
    {
        return $this->sendRaw('forwardMessage', [
            'from_chat_id' => $fromChatId,
            'message_id' => $messageId,
            'chat_id' => $toChatId,
            'disable_web_page_preview' => true,
        ]);
    }

    public function sendMessageWithInlineButtons(int $chatId, string $message, array $buttons): array
    {
        return $this->sendRaw('sendMessage', [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
            'reply_markup' => [
                'inline_keyboard' => $buttons,
            ],
        ]);
    }

    public function sendMessageWithButtons(int $chatId, string $message, array $buttons, bool $isPersistent = true): array
    {
        return $this->sendRaw('sendMessage', [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
            'reply_markup' => [
                'keyboard' => $buttons,
                'is_persistent' => $isPersistent,
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ],
        ]);
    }

    public function deleteMessage(int $chatId, int $messageId): array
    {
        return $this->sendRaw('deleteMessage', [
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ]);
    }

    public function getChat(int $chatId): array
    {
        return $this->sendRaw('getChat', [
            'chat_id' => $chatId,
        ]);
    }

    public function getChatMember(int $chatId, int $userId): array
    {
        return $this->sendRaw('getChatMember', [
            'chat_id' => $chatId,
            'user_id' => $userId,
        ]);
    }

    public function banChatMember(int $chatId, int $userId): array
    {
        return $this->sendRaw('banChatMember', [
            'chat_id' => $chatId,
            'user_id' => $userId,
            'revoke_messages' => false,
        ]);
    }

    public function unbanChatMember(int $chatId, int $userId): array
    {
        return $this->sendRaw('unbanChatMember', [
            'chat_id' => $chatId,
            'user_id' => $userId,
            'revoke_messages' => false,
        ]);
    }

    public function sendChatAction(int $chatId, string $action): array
    {
        return $this->sendRaw('sendChatAction', [
            'chat_id' => $chatId,
            'action' => $action,
        ]);
    }

    public function getFile(string $fileId): string
    {
        $fileInfo = $this->sendRaw('getFile', ['file_id' => $fileId]);

        return file_get_contents('https://api.telegram.org/file/bot'.config('services.telegram.token').'/'.$fileInfo['result']['file_path']);
    }

    public function sendPoll(
        int $chatId,
        string $question,
        array $options,
        bool $isAnonymous = true,
        bool $allowsMultipleAnswers = false
    ): array {
        return $this->sendRaw('sendPoll', [
            'chat_id' => $chatId,
            'question' => $question,
            'options' => $options,
            'is_anonymous' => $isAnonymous,
            'allows_multiple_answers' => $allowsMultipleAnswers,
        ]);
    }

    public function setWebhook(): array
    {
        return $this->sendRaw('setWebhook', [
            'url' => config('services.telegram.url'),
        ]);
    }

    public function getWebhookInfo(): array
    {
        return $this->sendRaw('getWebhookInfo');
    }
}
