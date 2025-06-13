<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModerationController extends Controller
{
    public function index(Request $request)
    {
        // LOG: Mencatat awal eksekusi fungsi dengan parameter yang diterima
        Log::info('Membuka halaman laporan (moderation.reports)', [
            'type' => $request->query('type', 'question'),
            'page' => $request->query('page', 1),
            'search' => $request->query('search', ''),
            'ip_address' => $request->ip()
        ]);

        // Tentukan tipe laporan aktif, default ke 'question'
        $activeType = $request->query('type', 'question');
        $page = $request->query('page', 1);
        $search = $request->query('search', '');

        // Siapkan URL dan parameter untuk API call
        $apiUrl = env('API_URL') . '/admin/reports';
        $queryParams = [
            'type' => $activeType,
            'page' => $page,
            'search' => $search,
            'per_page' => 5, // Atau sesuai kebutuhan
        ];

        // LOG: Mencatat detail panggilan API yang akan dilakukan
        Log::info('Mempersiapkan panggilan API untuk mengambil laporan.', [
            'url' => $apiUrl,
            'params' => $queryParams
        ]);

        // Panggil API
        $response = Http::withToken(session('token'))->get($apiUrl, $queryParams);

        if ($response->failed()) {
            // LOG: Mencatat kegagalan panggilan API dengan konteks yang lengkap
            Log::error('Panggilan API untuk mengambil laporan gagal.', [
                'url' => $apiUrl,
                'params' => $queryParams,
                'status_code' => $response->status(),
                'response_body' => $response->body()
            ]);
            
            // Kembali ke view dengan data kosong dan pesan error
            $reports = new LengthAwarePaginator([], 0, 5);
            return view('moderation.contents', ['reports' => $reports, 'type' => $activeType])
                    ->with('error', 'Failed to fetch reports from the server.');
        }

        $responseData = $response->json();
        
        // LOG: Mencatat bahwa API call berhasil dan memberikan ringkasan data
        Log::info('Panggilan API untuk laporan berhasil.', [
            'total_items' => $responseData['meta']['total'] ?? 'N/A',
            'current_page' => $responseData['meta']['current_page'] ?? 'N/A'
        ]);

        // KUNCI UTAMA: Buat ulang Paginator agar bisa digunakan oleh Blade
        // Ini memungkinkan kita menggunakan {{ $reports->links() }} di view
        $reports = new LengthAwarePaginator(
            $responseData['data'], // Data item untuk halaman ini
            $responseData['meta']['total'], // Total semua item
            $responseData['meta']['per_page'], // Item per halaman
            $responseData['meta']['current_page'], // Halaman saat ini
            [
                'path' => $request->url(), // Path dasar untuk link pagination
                'query' => $request->query(), // Sertakan query string lain seperti 'type'
            ]
        );
        
        // LOG: Konfirmasi bahwa view akan dirender dengan data
        Log::info('Berhasil memproses data dan akan menampilkan view moderation.reports.');

        return view('moderation.contents', [
            'reports' => $reports,
            'type' => $activeType, // Kirim tipe aktif ke view
        ]);
    }
}