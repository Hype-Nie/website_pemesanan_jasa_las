<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Generate an 8-character random password, update the user's password,
     * and send it to the user's email via SMTP.
     */
    public function sendResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Alamat email tidak ditemukan dalam database kami.']);
        }

        // Generate an 8-character secure random alphanumeric password
        $randomPassword = Str::password(8, letters: true, numbers: true, symbols: false);

        // Update user's password
        $user->password = Hash::make($randomPassword);
        $user->save();

        // Send email via SMTP
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($user, $randomPassword));

            return back()->with('status', 'Password baru 8 karakter berhasil di-generate dan dikirim ke alamat email Anda (' . $user->email . '). Silakan periksa kotak masuk atau spam.');
        } catch (\Throwable $e) {
            Log::error('SMTP Reset Password Error: ' . $e->getMessage(), [
                'email' => $user->email,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Gagal mengirim email melalui SMTP: ' . $e->getMessage() . '. Pastikan pengaturan SMTP di file .env sudah sesuai.');
        }
    }
}
