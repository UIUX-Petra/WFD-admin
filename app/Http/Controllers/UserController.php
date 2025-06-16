<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    public function getAllUsers(Request $request)
    {
        Log::info('Membuka halaman data pengguna (users.index)', [
            'page' => $request->query('page', 1),
            'search' => $request->query('search', ''),
            'status' => $request->query('status', 'all'),
            'ip_address' => $request->ip()
        ]);

        // ... (logika untuk mengambil data paginasi dari API tetap sama) ...
        $page = $request->query('page', 1);
        $search = $request->query('search', '');
        $status = $request->query('status', 'all');
        $apiUrl = env('API_URL') . '/admin/users/basic-info';
        $queryParams = ['page' => $page, 'search' => $search, 'status' => $status];
        $response = Http::withToken(session('token'))->get($apiUrl, $queryParams);

        if ($response->failed()) {
            // ... (logika error tetap sama) ...
            $users = new LengthAwarePaginator([], 0, 10, 1);
            return view('users.index', [
                'users' => $users,
                'search' => $search,
                'status' => $status,
                'apiUrl' => env('API_URL'), // Tambahkan ini
                'apiToken' => session('token') // Tambahkan ini
            ])->with('error', 'Gagal mengambil data pengguna.');
        }

        $responseData = $response->json();
        if (!isset($responseData['data']['data'])) {
            // ... (logika error tetap sama) ...
            $users = new LengthAwarePaginator([], 0, 10, 1);
             return view('users.index', [
                'users' => $users,
                'search' => $search,
                'status' => $status,
                'apiUrl' => env('API_URL'), // Tambahkan ini
                'apiToken' => session('token') // Tambahkan ini
            ])->with('error', 'Terjadi kesalahan saat memproses data.');
        }

        $apiPaginatorData = $responseData['data'];
        $users = new LengthAwarePaginator(
            $apiPaginatorData['data'],
            $apiPaginatorData['total'],
            $apiPaginatorData['per_page'],
            $apiPaginatorData['current_page'],
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('users.index', [
            'users' => $users,
            'search' => $search,
            'status' => $status,
            'apiUrl' => env('API_URL'),      // Kirim API URL ke view
            'apiToken' => session('token'), // Kirim token API ke view
        ]);
    }
    

    // Metode blockUser dan unblockUser TIDAK DIPERLUKAN LAGI di controller frontend ini.
    // Hapus kedua fungsi di bawah ini.
    // public function blockUser(Request $request, $userId) { ... }
    // public function unblockUser(Request $request, $userId) { ... }
}