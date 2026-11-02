<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PackingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackingController extends Controller
{
    public function index(Request $request)
    {
        $lists = PackingList::with('items')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('tgl_mulai')
            ->get();

        return response()->json(['status' => 'success', 'data' => $lists]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perjalanan' => 'required|string|max:150',
            'destinasi' => 'nullable|string|max:150',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:Clothing_Items,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $packingList = DB::transaction(function () use ($validated, $request) {
            $list = PackingList::create([
                'user_id' => $request->user()->id,
                'nama_perjalanan' => $validated['nama_perjalanan'],
                'destinasi' => $validated['destinasi'] ?? null,
                'tgl_mulai' => $validated['tgl_mulai'],
                'tgl_selesai' => $validated['tgl_selesai'],
            ]);

            $pivot = [];
            foreach ($validated['items'] as $item) {
                $pivot[$item['item_id']] = [
                    'jumlah' => $item['jumlah'],
                    'is_packed' => false,
                    'updated_at' => now(),
                ];
            }

            $list->items()->sync($pivot);
            return $list->load('items');
        });

        return response()->json(['status' => 'success', 'data' => $packingList], 201);
    }

    public function togglePacked(Request $request, int $packingId, int $itemId)
    {
        $list = PackingList::where('user_id', $request->user()->id)->findOrFail($packingId);
        $validated = $request->validate(['is_packed' => 'required|boolean']);

        $list->items()->updateExistingPivot($itemId, [
            'is_packed' => $validated['is_packed'],
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Status packing diperbarui.']);
    }
}<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PackingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackingController extends Controller
{
    public function index(Request $request)
    {
        $lists = PackingList::with('items')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('tgl_mulai')
            ->get();

        return response()->json(['status' => 'success', 'data' => $lists]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perjalanan' => 'required|string|max:150',
            'destinasi' => 'nullable|string|max:150',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:Clothing_Items,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $packingList = DB::transaction(function () use ($validated, $request) {
            $list = PackingList::create([
                'user_id' => $request->user()->id,
                'nama_perjalanan' => $validated['nama_perjalanan'],
                'destinasi' => $validated['destinasi'] ?? null,
                'tgl_mulai' => $validated['tgl_mulai'],
                'tgl_selesai' => $validated['tgl_selesai'],
            ]);

            $pivot = [];
            foreach ($validated['items'] as $item) {
                $pivot[$item['item_id']] = [
                    'jumlah' => $item['jumlah'],
                    'is_packed' => false,
                    'updated_at' => now(),
                ];
            }

            $list->items()->sync($pivot);
            return $list->load('items');
        });

        return response()->json(['status' => 'success', 'data' => $packingList], 201);
    }

    public function togglePacked(Request $request, int $packingId, int $itemId)
    {
        $list = PackingList::where('user_id', $request->user()->id)->findOrFail($packingId);
        $validated = $request->validate(['is_packed' => 'required|boolean']);

        $list->items()->updateExistingPivot($itemId, [
            'is_packed' => $validated['is_packed'],
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Status packing diperbarui.']);
    }
}