<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\Services\LCReportDataService;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');




Artisan::command('lc:import-report-data', function () {
    $this->info('Starting the import of LC Report Data.');

    // Get yesterday's date (adjust as needed)
    $date = Carbon::yesterday()->format('Y-m-d');

    $this->info('Importing data for date: ' . $date);

    // Resolve the service from the container
    $lcReportDataService = app(LCReportDataService::class);

    // Call the service to import the report data
    $result = $lcReportDataService->importReportData($date);

    if ($result) {
        $this->info('Report data imported successfully.');
        Log::info('Report data imported successfully for date: ' . $date);
    } else {
        $this->error('Failed to import report data.');
        Log::error('Failed to import report data for date: ' . $date);
    }
})->purpose('Import LC Report Data')
  ->dailyAt('9:15')
  ->timezone('America/New_York')
  ->withoutOverlapping()
  ->onOneServer()
  ->appendOutputTo(storage_path('logs/import_lc_report_data.log'));
