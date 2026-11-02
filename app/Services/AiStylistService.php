<?php

namespace App\Services;

use App\Models\Category;
use App\Models\ClothingItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AiStylistService
{
    /**
     * Flowchart 3A: Kirim gambar ke API AI untuk Auto-Tagging (Kategori & Warna)
     * Fallback: Jika API AI gagal/timeout, kembalikan ai_success = false agar user isi manual.
     */
    public function autoTagGarment(string $imageUrl): array
    {
        $categories = Category::orderBy('urutan_tampil')->pluck('nama_kategori', 'id')->toArray();

        try {
            $response = Http::timeout(config('services.ai_stylist.timeout'))
                ->withToken(config('services.ai_stylist.key'))
                ->post(config('services.ai_stylist.url'), [
                    'model' => 'gpt-4o-mini',
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Anda adalah AI Fashion Taxonomist. Analisis gambar pakaian dan kembalikan JSON dengan key: category_id (pilih dari: ' . json_encode($categories) . '), warna, style_occasion, dan tag (dipisahkan koma).'
                        ],
                        [
                            'role' => 'user',
                            'content' => [
                                ['type' => 'image_url', 'image_url' => ['url' => $imageUrl]]
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $parsed = json_decode($response->json('choices.0.message.content'), true);

                if (is_array($parsed) && isset($parsed['category_id'])) {
                    return [
                        'ai_success' => true,
                        'attributes' => [
                            'category_id' => (int) $parsed['category_id'],
                            'warna' => $parsed['warna'] ?? null,
                            'style_occasion' => $parsed['style_occasion'] ?? 'Casual',
                            'tag' => $parsed['tag'] ?? null,
                        ],
                    ];
                }
            }
        } catch (Throwable $e) {
            Log::warning('AI Auto-Tagging Fallback Triggered: ' . $e->getMessage());
        }

        // Fallback sesuai Flowchart 3A: User isi kategori & warna secara manual
        return [
            'ai_success' => false,
            'attributes' => [
                'category_id' => null,
                'warna' => null,
                'style_occasion' => null,
                'tag' => null,
            ],
        ];
    }

    /**
     * Flowchart 3B: Multi-Method Outfit Builder
     * - Method 2 ("Key Item AI"): User memilih 1 item kunci, AI melengkapi sisanya.
     * - Method 3 ("Full AI Builder"): AI menyusun outfit penuh berdasarkan event & cuaca.
     */
    public function curateOutfit(int $userId, string $method, array $context): array
    {
        $wardrobe = ClothingItem::with('category')
            ->where('user_id', $userId)
            ->get(['id', 'category_id', 'nama_item', 'warna', 'style_occasion', 'tag', 'url_gambar']);

        if ($wardrobe->isEmpty()) {
            return ['ai_success' => false, 'message' => 'Wardrobe masih kosong.', 'items' => []];
        }

        try {
            $response = Http::timeout(config('services.ai_stylist.timeout'))
                ->withToken(config('services.ai_stylist.key'))
                ->post(config('services.ai_stylist.url'), [
                    'model' => 'gpt-4o-mini',
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Pilih kombinasi outfit dari daftar wardrobe user. Kembalikan JSON: {"nama_outfit": "...", "mood_event": "...", "selected_items": [{"item_id": 1, "pos_x": 40.0, "pos_y": 50.0, "z_index": 1, "scale": 1.0, "is_key_item": 1}]}'
                        ],
                        [
                            'role' => 'user',
                            'content' => json_encode([
                                'method' => $method,
                                'key_item_id' => $context['key_item_id'] ?? null,
                                'event' => $context['event'] ?? 'Daily Casual',
                                'weather' => $context['weather'] ?? null,
                                'wardrobe' => $wardrobe->toArray(),
                            ])
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $result = json_decode($response->json('choices.0.message.content'), true);
                if (!empty($result['selected_items'])) {
                    return [
                        'ai_success' => true,
                        'nama_outfit' => $result['nama_outfit'] ?? 'AI Curated Look',
                        'mood_event' => $result['mood_event'] ?? ($context['event'] ?? 'Casual'),
                        'items' => $result['selected_items'],
                    ];
                }
            }
        } catch (Throwable $e) {
            Log::warning('AI Curate Fallback Triggered: ' . $e->getMessage());
        }

        // Rule-Based Local Fallback: Ambil 1 item per kategori secara otomatis jika API AI down
        $fallbackItems = [];
        $keyItemId = $context['key_item_id'] ?? null;
        $grouped = $wardrobe->groupBy('category_id');
        $zIndex = 1;

        foreach ($grouped as $categoryId => $items) {
            $chosen = $keyItemId
                ? ($items->firstWhere('id', $keyItemId) ?? $items->random())
                : $items->random();

            $fallbackItems[] = [
                'item_id' => $chosen->id,
                'pos_x' => 50.0 + ($zIndex * 30),
                'pos_y' => 40.0 + ($zIndex * 45),
                'z_index' => $zIndex,
                'scale' => 1.0,
                'is_key_item' => ($chosen->id == $keyItemId) ? 1 : 0,
            ];
            $zIndex++;
        }

        return [
            'ai_success' => false,
            'nama_outfit' => 'Smart Mix & Match',
            'mood_event' => $context['event'] ?? 'Daily Look',
            'items' => $fallbackItems,
        ];
    }
}