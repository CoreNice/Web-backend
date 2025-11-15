<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;

class PasswordResetController extends Controller
{
    /**
     * 1. Generate token & send reset link
     */
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email tidak terdaftar'], 404);
        }

        // Buat token
        $token = Str::random(64);

        // Simpan token
        PasswordResetToken::create([
            'email'      => $request->email,
            'token'      => $token,
            'expires_at' => Carbon::now()->addMinutes(30)
        ]);

        // Kirim email reset
        Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));

        return response()->json([
            'message' => 'Link reset password telah dikirim ke email'
        ]);
    }


    /**
     * 2. Verify token (dipanggil oleh Next.js sebelum membuka halaman reset)
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email'
        ]);

        $record = PasswordResetToken::where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Token tidak valid'], 404);
        }

        if ($record->isExpired()) {
            return response()->json(['message' => 'Token sudah expired'], 410);
        }

        return response()->json(['message' => 'Token valid']);
    }


    /**
     * 3. Reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6'
        ]);

        $record = PasswordResetToken::where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Token tidak valid'], 404);
        }

        if ($record->isExpired()) {
            return response()->json(['message' => 'Token sudah expired'], 410);
        }

        // Update password user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token setelah dipakai
        $record->delete();

        return response()->json([
            'message' => 'Password berhasil diperbarui'
        ]);
    }
}
