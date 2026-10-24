<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Contoh penjadwalan setiap minggu sekali (misal setiap hari Minggu pukul 00:00)
Schedule::command('db:sqlite-backup')->weekly();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
