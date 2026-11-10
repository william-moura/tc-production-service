<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('production:start-consumers', function () {
    $this->comment('Starting production consumers...');
    $this->call('production:consume');
})->purpose('Start production consumers');