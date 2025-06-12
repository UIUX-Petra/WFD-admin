<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman/form login untuk admin.
     */
    public function showLoginForm()
    {
        return view('login'); // Pastikan Anda punya view ini
    }

    /**
     * Memproses data dari form login, mengirimkannya ke API, dan menangani hasilnya.
     */
    public function login(Request $request)
    {
        // 1. Validasi input dari form di sisi frontend
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Siapkan URL endpoint API
        $apiUrl = env('API_URL') . '/admin/login';

        // 3. Kirim request POST ke API dengan kredensial
        $response = Http::post($apiUrl, $credentials);

        // 4. Tangani jika request ke API GAGAL
        // Contoh: API mengembalikan error 422 karena kredensial salah
        if ($response->failed()) {
            return back()->with('Error', $response['message'])->withInput();
        }

        // 5. Jika request ke API BERHASIL
        $responseData = $response->json();
        $responseData = $responseData['data'];


        // 6. Simpan informasi penting ke dalam session frontend
        session([
            'admin_id' => $responseData['admin']['id'],
            'admin_name' => $responseData['admin']['name'],
            'admin_email' => $responseData['admin']['email'],
            'admin_token' => $responseData['token'], // <-- Token sakti dengan abilities
            'admin_roles' => collect($responseData['admin']['roles'])->pluck('name')->toArray(), // Simpan nama role
        ]);

        // 7. Arahkan admin ke halaman dashboard mereka
        return redirect()->route('admin.dashboard'); // Ganti dengan nama route dashboard Anda
    }

    /**
     * Menangani logout admin.
     */
    public function logout(Request $request)
    {
        $apiUrl = env('API_URL') . '/admin/logout';
        
        // Kirim request ke API untuk menghapus token di sisi server
        // Gunakan ->withToken() untuk otentikasi
        Http::withToken(session('admin_token'))->post($apiUrl);
        
        // Hapus semua data session di sisi frontend
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}