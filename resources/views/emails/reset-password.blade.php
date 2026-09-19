<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Baru Akun Anda</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #f4f6f9; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #e1e8ed;">
        <!-- Header -->
        <div style="background-color: #ea580c; padding: 24px 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px;">Bengkel Las Asyraf</h1>
            <p style="color: #ffedd5; margin: 5px 0 0 0; font-size: 14px;">Layanan Pemesanan Jasa Las Berkualitas</p>
        </div>

        <!-- Body -->
        <div style="padding: 30px;">
            <h2 style="color: #1f2937; font-size: 18px; margin-top: 0;">Halo, {{ $user->name }}!</h2>
            <p style="color: #4b5563; font-size: 15px;">Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Sistem kami telah membuatkan kata sandi acak baru yang aman sepanjang 8 karakter untuk Anda:</p>

            <!-- Password Box -->
            <div style="background-color: #f3f4f6; border: 2px dashed #ea580c; border-radius: 6px; padding: 16px; text-align: center; margin: 25px 0;">
                <span style="font-size: 12px; color: #6b7280; text-transform: uppercase; display: block; margin-bottom: 6px; font-weight: bold;">Kata Sandi Baru Anda:</span>
                <span style="font-size: 26px; font-weight: bold; color: #ea580c; letter-spacing: 3px; font-family: 'Courier New', Courier, monospace;">{{ $newPassword }}</span>
            </div>

            <!-- Login CTA -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('login') }}" style="background-color: #ea580c; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 15px; display: inline-block;">Masuk ke Akun Sekarang</a>
            </div>

            <!-- Security Warning -->
            <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 12px 16px; margin-top: 25px; border-radius: 0 6px 6px 0;">
                <p style="margin: 0; font-size: 13px; color: #92400e;">
                    <strong>Tips Keamanan:</strong> Demi keamanan akun Anda, segera ganti kata sandi ini dengan kata sandi pribadi Anda setelah berhasil masuk. Jika Anda tidak pernah meminta reset kata sandi, hubungi admin kami segera.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f9fafb; border-top: 1px solid #e5e7eb; padding: 16px 30px; text-align: center; font-size: 12px; color: #9ca3af;">
            <p style="margin: 0;">Bengkel Asyraf - Talaga, Bone, Sulawesi Selatan</p>
            <p style="margin: 4px 0 0 0;">Email otomatis, mohon tidak membalas langsung ke alamat ini.</p>
        </div>
    </div>
</body>
</html>
