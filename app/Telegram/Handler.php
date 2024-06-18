<?php

namespace App\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;
use Illuminate\Http\Request;
use GuzzleHttp\Client;



class Handler extends WebhookHandler
{
    public function help (): void
    {
        $this->reply("Меня зовут Сигма Бот и я могу помочь вам с вашими вопросами в офисе.
Спросите меня о чём-нибудь и я обязательно вам помогу!");
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        if ($text->value() === "/start") {
            $this->reply('Добро пожаловать!
Меня зовут Сигма Бот, буду рад вам помочь!');
        } else {
            $this->reply('я не знаю такой команды:(');
        }
    }

    protected function handleChatMessage(Stringable $text): void
    {
//        $message = $text->value();
//        $newMessage = new Message(['text' => $text->value()]);
//        $message = new Message(['text' => $text->value()]);
//        $messageType = $message->getType();

//        $processedMessage = $this->sendToPythonAPI($message);

//        $this->sendMessageToUser($processedMessage);
        if ($text->isNotEmpty()) {
            $message = $text->value();
            $processedMessage = $this->sendToPythonAPI($message);
            $this->sendMessageToUser($processedMessage);
        } else {
            $this->reply('К сожалению, я пока не могу обрабатывать не текстовые сообщения');
        }
    }

    public function handleMessage(Request $request)
    {
        $message = $request->input('message.text');

        // Передаем сообщение в Python-код для обработки
        $processedMessage = $this->sendToPythonAPI($message);

        // Отправляем обработанное сообщение пользователю
        $this->sendMessageToUser($processedMessage);
    }

    private function sendToPythonAPI($message)
    {
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/process_message', [
            'json' => ['message' => $message]
        ]);

        Log::info('Sent message to Python API: ' . $message);

        $responseData = json_decode($response->getBody(), true);
        return $responseData['message'];
    }

    private function sendMessageToUser($message)
    {
        try {
            $this->reply($message);
            // Логгирование успешной отправки
            $this->logger->info('Message sent to user: ' . $message);
        } catch (\Exception $e) {
            // Логгирование ошибки
//            $this->logger->error('Error sending message to user: ' . $e->getMessage());
        }
    }
}
