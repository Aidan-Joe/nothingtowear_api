<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ClothingItem;
use App\Services\AiStylistService;
use Illuminate\Http\Request;

class WardrobeController extends Controller
{
    public function __construct(private AiStylistService $aiStylistService) {}

    public function categories()
    {
        return response()->json([
            'status' => 'success',
            'data' => Category::orderBy('urutan_tampil')->get(),
        ]);
    }

    public function index(Request $request)
    {
        $query = ClothingItem::with('category')
            ->where('user_id', $request->user()->id);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('style_occasion')) {
            $query->where('style_occasion', $request->string('style_occasion'));
        }

        if ($request->boolean('is_favorite')) {
            $query->where('is_favorite', true);
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_item', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('tag', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->orderByDesc('created_at')->get(),
        ]);
    }

    /**
     * Step 1 Flowchart 3A: Validasi file foto -> Simpan ke storage -> Kirim ke API AI Auto-Tagging
     */
    public function uploadAndAutoTag(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Maks 5MB
        ], [
            'image.mimes' => 'Format tidak valid',
            'image.max' => 'Ukuran tidak sesuai',
        ]);

        $path = $request->file('image')->store('wardrobe', 'public');
        $fullUrl = asset('storage/' . $path);

        $aiResult = $this->aiStylistService->autoTagGarment($fullUrl);

        return response()->json([
            'status' => 'success',
            'ai_tagging_success' => $aiResult['ai_success'],
            'url_gambar' => $fullUrl,
            'detected_attributes' => $aiResult['attributes'],
        ]);
    }

    /**
     * Step 2 Flowchart 3A: User cek & lengkapi brand/ukuran -> Simpan ke Clothing_Items
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:Categories,id',
            'nama_item' => 'required|string|max:150',
            'warna' => 'required|string|max:50',
            'brand' => 'required|string|max:100',
            'ukuran' => 'required|string|max:20',
            'style_occasion' => 'nullable|string|max:100',
            'tag' => 'nullable|string|max:255',
            'url_gambar' => 'required|string|max:255',
            'is_favorite' => 'nullable|boolean',
        ]);

        $item = ClothingItem::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'is_favorite' => $validated['is_favorite'] ?? false,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item->load('category'),
        ], 201);
    }

    public function destroy(Request $request, int $id)
    {
        $item = ClothingItem::where('user_id', $request->user()->id)->findOrFail($id);
        $item->delete();

        return response()->json(['status' => 'success', 'message' => 'Pakaian dihapus dari wardrobe.']);
    }
}