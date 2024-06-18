<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('tester', function () {
    $bot = \DefStudio\Telegraph\Models\TelegraphBot::find(1);

    dd($bot->registerCommands([
        'help' => 'что умеет этот бот',
        'start' => 'начать работу с ботом',
    ])->send());
});
