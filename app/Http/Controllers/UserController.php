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

        $page = $request->query('page', 1);
        $search = $request->query('search', '');
        $status = $request->query('status', 'all');
        $apiUrl = env('API_URL') . '/admin/users/basic-info';
        $queryParams = ['page' => $page, 'search' => $search, 'status' => $status];
        $response = Http::withToken(session('token'))->get($apiUrl, $queryParams);

        if ($response->failed()) {
            $users = new LengthAwarePaginator([], 0, 10, 1);
            return view('users.index', [
                'users' => $users,
                'search' => $search,
                'status' => $status,
                'apiUrl' => env('API_URL'), 
                'apiToken' => session('token') 
            ])->with('error', 'Gagal mengambil data pengguna.');
        }

        $responseData = $response->json();
        if (!isset($responseData['data']['data'])) {
            $users = new LengthAwarePaginator([], 0, 10, 1);
            return view('users.index', [
                'users' => $users,
                'search' => $search,
                'status' => $status,
                'apiUrl' => env('API_URL'), 
                'apiToken' => session('token') 
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
            'apiUrl' => env('API_URL'),     
            'apiToken' => session('token'), 
        ]);
    }



public function showActivity(Request $request, $userId)
{
    Log::info("Membuka halaman aktivitas untuk user ID: {$userId}");

    $apiUrl = env('API_URL') . '/admin/users/' . $userId . '/activity';
    $response = Http::withToken(session('token'))->get($apiUrl);

    if ($response->failed()) {
        Log::error("Gagal mengambil data aktivitas untuk user ID: {$userId}", [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        return redirect()->route('admin.users.index')->with('error', 'Could not retrieve user activity data.');
    }

    $apiData = $response->json()['data'];

    return view('users.activity', [
        'user' => (object) $apiData['user'], // Ubah array 'user' dari JSON menjadi objek
        'stats' => $apiData['stats'],
        'activities' => $apiData['activities'],
    ]);
}
}
