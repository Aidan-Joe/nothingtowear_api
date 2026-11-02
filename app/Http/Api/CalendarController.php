<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarEntry;
use App\Models\Notification;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function __construct(private WeatherService $weatherService) {}

    public function index(Request $request)
    {
        $entries = CalendarEntry::with('outfit.items')
            ->where('user_id', $request->user()->id)
            ->orderBy('tanggal')
            ->orderBy('waktu')
            ->get();

        return response()->json(['status' => 'success', 'data' => $entries]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outfit_id' => 'required|exists:Outfits,id',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'lokasi' => 'nullable|string|max:150',
            'event' => 'required|string|max:150',
        ]);

        $lokasi = $validated['lokasi'] ?? $request->user()->lokasi_default ?? 'Jakarta, Indonesia';
        $weather = $this->weatherService->getForecast($lokasi, $validated['tanggal']);

        $entry = DB::transaction(function () use ($validated, $request, $lokasi, $weather) {
            $calendarEntry = CalendarEntry::create([
                'user_id' => $request->user()->id,
                'outfit_id' => $validated['outfit_id'],
                'tanggal' => $validated['tanggal'],
                'waktu' => $validated['waktu'],
                'lokasi' => $lokasi,
                'event' => $validated['event'],
                'kondisi_cuaca' => $weather['kondisi_cuaca'],
                'suhu' => $weather['suhu'],
            ]);

            Notification::create([
                'user_id' => $request->user()->id,
                'calendar_id' => $calendarEntry->id,
                'judul' => 'Jadwal OOTD: ' . $validated['event'],
                'pesan' => 'Outfit telah dijadwalkan untuk tanggal ' . $validated['tanggal'] . ' pukul ' . $validated['waktu'] . '.',
                'tipe_notifikasi' => 'OOTD Reminder',
                'is_read' => false,
            ]);

            return $calendarEntry->load('outfit.items');
        });

        return response()->json([
            'status' => 'success',
            'weather_fetched' => $weather['weather_available'],
            'data' => $entry,
        ], 201);
    }
}