protected function schedule(Schedule $schedule)
{
    $schedule->command('recordatorios:revision')->dailyAt('09:00');
}