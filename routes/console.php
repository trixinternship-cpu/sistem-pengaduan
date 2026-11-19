<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Arsipkan otomatis setiap hari pukul 00.05 setelah melewati 7 hari selesai.
Schedule::command('complaints:archive-expired')->everyMinute()->withoutOverlapping();
