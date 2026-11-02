<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WeatherService
{
    /**
     * Flowchart 3B: Minta data cuaca ke API Cuaca.
     * Jika gagal/timeout -> Lanjutkan tanpa info cuaca (kondisi_cuaca & suhu = null).
     */
    public function getForecast(string $location, string $date): array
    {
        try {
            $response = Http::timeout(config('services.weather.timeout'))
                ->get(config('services.weather.url'), [
                    'q' => $location,
                    'appid' => config('services.weather.key'),
                    'units' => 'metric',
                    'lang' => 'id',
                ]);

            if ($response->successful() && !empty($response->json('list'))) {
                $forecasts = collect($response->json('list'));

                // Cari prakiraan yang tanggalnya cocok dengan parameter $date
                $matched = $forecasts->first(function ($item) use ($date) {
                    return str_starts_with($item['dt_txt'] ?? '', $date);
                }) ?? $forecasts->first();

                return [
                    'weather_available' => true,
                    'kondisi_cuaca' => ucfirst($matched['weather'][0]['description'] ?? 'Cerah Berawan'),
                    'suhu' => isset($matched['main']['temp']) ? round((float) $matched['main']['temp'], 1) : null,
                ];
            }
        } catch (Throwable $e) {
            Log::warning('Weather API Fallback Triggered: ' . $e->getMessage());
        }

        // Non-weather fallback sesuai Flowchart 3B
        return [
            'weather_available' => false,
            'kondisi_cuaca' => null,
            'suhu' => null,
        ];
    }
}