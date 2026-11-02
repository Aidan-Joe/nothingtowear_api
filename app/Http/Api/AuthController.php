<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:Users,email',
            'password' => 'required|string|min:8',
            'lokasi_default' => 'nullable|string|max:150',
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'lokasi_default' => $validated['lokasi_default'] ?? 'Jakarta, Indonesia',
        ]);

        $token = $user->createToken('flutter-mobile-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password_hash)) {
            return response()->json(['status' => 'error', 'message' => 'Email atau password salah.'], 401);
        }

        $token = $user->createToken('flutter-mobile-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'wardrobe_stats' => [
                'total_pakaian' => $user->clothingItems()->count(),
                'total_curated_outfits' => $user->outfits()->count(),
            ],
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password_hash)) {
            return response()->json(['status' => 'error', 'message' => 'Password lama tidak sesuai.'], 422);
        }

        $user->update([
            'password_hash' => Hash::make($validated['new_password']),
            'password_updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Password berhasil diperbarui.']);
    }

    public function notifications(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['status' => 'success', 'data' => $notifications]);
    }

    public function markNotificationRead(Request $request, int $id)
    {
        $notification = Notification::where('user_id', $request->user()->id)->findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json(['status' => 'success', 'data' => $notification]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => 'success', 'message' => 'Berhasil logout.']);
    }
}