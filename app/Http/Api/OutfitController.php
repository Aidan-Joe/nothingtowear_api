<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiRecommendation;
use App\Models\Outfit;
use App\Services\AiStylistService;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutfitController extends Controller
{
    public function __construct(
        private AiStylistService $aiStylistService,
        private WeatherService $weatherService
    ) {}

    public function index(Request $request)
    {
        $outfits = Outfit::with('items')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['status' => 'success', 'data' => $outfits]);
    }

    /**
     * Endpoint untuk Method 2 (Key Item AI) & Method 3 (Full AI Builder + Weather)
     */
    public function generateAiOutfit(Request $request)
    {
        $validated = $request->validate([
            'metode_pembuatan' => 'required|in:Key Item AI,Full AI Builder',
            'key_item_id' => 'required_if:metode_pembuatan,Key Item AI|nullable|integer',
            'event' => 'nullable|string|max:150',
            'lokasi' => 'nullable|string|max:150',
            'tanggal' => 'nullable|date',
        ]);

        $weatherInfo = null;
        if ($validated['metode_pembuatan'] === 'Full AI Builder' && !empty($validated['lokasi'])) {
            $weatherInfo = $this->weatherService->getForecast(
                $validated['lokasi'],
                $validated['tanggal'] ?? now()->toDateString()
            );
        }

        $curated = $this->aiStylistService->curateOutfit(
            $request->user()->id,
            $validated['metode_pembuatan'],
            [
                'key_item_id' => $validated['key_item_id'] ?? null,
                'event' => $validated['event'] ?? null,
                'weather' => $weatherInfo,
            ]
        );

        return response()->json([
            'status' => 'success',
            'weather_context' => $weatherInfo,
            'curated_outfit' => $curated,
        ]);
    }

    /**
     * Simpan Outfit & Koordinat Kanvas (pos_x, pos_y, z_index, scale) ke Outfits & Outfit_Items
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'mood_event' => 'nullable|string|max:100',
            'metode_pembuatan' => 'required|in:Manual,Key Item AI,Full AI Builder',
            'preview_url' => 'nullable|string|max:255',
            'is_favorite' => 'nullable|boolean',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:Clothing_Items,id',
            'items.*.pos_x' => 'required|numeric',
            'items.*.pos_y' => 'required|numeric',
            'items.*.z_index' => 'required|integer',
            'items.*.scale' => 'required|numeric',
            'items.*.is_key_item' => 'nullable|boolean',
        ]);

        $outfit = DB::transaction(function () use ($validated, $request) {
            $newOutfit = Outfit::create([
                'user_id' => $request->user()->id,
                'nama' => $validated['nama'],
                'mood_event' => $validated['mood_event'] ?? null,
                'metode_pembuatan' => $validated['metode_pembuatan'],
                'preview_url' => $validated['preview_url'] ?? null,
                'is_favorite' => $validated['is_favorite'] ?? false,
            ]);

            $pivotData = [];
            foreach ($validated['items'] as $canvasItem) {
                $pivotData[$canvasItem['item_id']] = [
                    'pos_x' => $canvasItem['pos_x'],
                    'pos_y' => $canvasItem['pos_y'],
                    'z_index' => $canvasItem['z_index'],
                    'scale' => $canvasItem['scale'],
                    'is_key_item' => $canvasItem['is_key_item'] ?? false,
                    'updated_at' => now(),
                ];
            }

            $newOutfit->items()->sync($pivotData);

            return $newOutfit->load('items');
        });

        return response()->json(['status' => 'success', 'data' => $outfit], 201);
    }

    public function dailyRecommendations(Request $request)
    {
        $recommendations = AiRecommendation::with('outfit.items')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('tanggal_rekomendasi')
            ->get();

        return response()->json(['status' => 'success', 'data' => $recommendations]);
    }
}