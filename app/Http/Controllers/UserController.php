<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUsers(Request $request)
    {
        Log::info('Membuka halaman data pengguna (users.index)', [
            'page' => $request->query('page', 1),
            'search' => $request->query('search', ''),
            'status' => $request->query('status', 'all'), // Menambahkan filter status
            'ip_address' => $request->ip()
        ]);

        $page = $request->query('page', 1);
        $search = $request->query('search', '');
        $status = $request->query('status', 'all');

        $apiUrl = env('API_URL') . '/admin/users/basic-info';
        $queryParams = [
            'page' => $page,
            'search' => $search,
            'status' => $status,
        ];

        Log::info('Mempersiapkan panggilan API untuk mengambil data pengguna.', [
            'url' => $apiUrl,
            'params' => $queryParams
        ]);

        $response = Http::withToken(session('token'))->get($apiUrl, $queryParams);

        if ($response->failed()) {
            Log::error('Panggilan API untuk mengambil data pengguna gagal.', [
                'url' => $apiUrl,
                'params' => $queryParams,
                'status_code' => $response->status(),
                'response_body' => $response->body()
            ]);

            $users = new LengthAwarePaginator([], 0, 10, 1);
            return view('users.index', [
                'users' => $users,
                'search' => $search, 
                'status' => $status, 
            ])->with('error', 'Gagal mengambil data pengguna dari server. Silakan coba lagi.');
        }

        $responseData = $response->json();

        if (!isset($responseData['data']['data'])) {
            Log::error('Format response API tidak sesuai harapan.', [
                'url' => $apiUrl,
                'response_body' => $response->body()
            ]);
            $users = new LengthAwarePaginator([], 0, 10, 1);
            return view('users.index', [
                'users' => $users,
                'search' => $search,
                'status' => $status,
            ])->with('error', 'Terjadi kesalahan saat memproses data dari server.');
        }

        $apiPaginatorData = $responseData['data'];

        Log::info('Panggilan API untuk data pengguna berhasil.', [
            'total_items' => $apiPaginatorData['total'] ?? 'N/A',
            'current_page' => $apiPaginatorData['current_page'] ?? 'N/A'
        ]);

        $users = new LengthAwarePaginator(
            $apiPaginatorData['data'],
            $apiPaginatorData['total'],
            $apiPaginatorData['per_page'],
            $apiPaginatorData['current_page'],
            [
                'path' => $request->url(),     
                'query' => $request->query(),  
            ]
        );

        Log::info('Berhasil memproses data dan akan menampilkan view users.index.');

        return view('users.index', [
            'users' => $users,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function blockUser(Request $request, $userId)
    {
        Log::info("Proxying request to block user ID: {$userId}");
        $apiUrl = env('API_URL') . '/admin/users/' . $userId . '/block';
         $queryParams = [
            'adminId' => session('id'),
        ];
        Log::info("Admin ID: {$userId}");

        $response = Http::withToken(session('token'))->post($apiUrl, $queryParams);
       
        if ($response->successful()) {
            Log::info("API call to block user was successful.", [
                'user_id' => $userId,
                'status_code' => $response->status(),
                'response_body' => $response->json()
            ]);
        } else {
            Log::error("API call to block user failed.", [
                'user_id' => $userId,
                'status_code' => $response->status(),
                'response_body' => $response->body() 
            ]);
        }

        return response()->json($response->json(), $response->status());
    }

    public function unblockUser(Request $request, $userId)
    {
        Log::info("Proxying request to unblock user ID: {$userId}");
        $apiUrl = env('API_URL') . '/admin/users/' . $userId . '/unblock';
        $response = Http::withToken(session('token'))->post($apiUrl);
        if ($response->successful()) {
            Log::info("API call to unblock user was successful.", [
                'user_id' => $userId,
                'status_code' => $response->status(),
                'response_body' => $response->json()
            ]);
        } else {
            Log::error("API call to unblock user failed.", [
                'user_id' => $userId,
                'status_code' => $response->status(),
                'response_body' => $response->body()
            ]);
        }
        return response()->json($response->json(), $response->status());
    }
}
