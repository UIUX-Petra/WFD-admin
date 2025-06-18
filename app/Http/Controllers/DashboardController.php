<?php

namespace App\Http\Controllers; // Pastikan namespace ini sesuai dengan file Anda

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function showMainDashboard()
    {
        return view('dashboard.index');
    }


    public function getStatisticsDataProxy(Request $request)
    {
        $period = $request->query('period', 'month');
        $token = session('token');

        $apiUrl = "http://localhost:8001/api/admin/dashboard/statistics?period={$period}";

        $response = Http::withToken($token)
            ->acceptJson()
            ->get($apiUrl);

        if ($response->failed()) {
            Log::error('Proxy to statistics API failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return response()->json(['message' => 'Failed to fetch data from API.'], 502);
        }

        return $response->json();
    }

    public function getReportDataProxy(Request $request)
    {
        $period = $request->query('period', 'month');

        $token = session('token');

        $apiUrl = "http://localhost:8001/api/admin/dashboard/report-stats?period={$period}";

        $response = Http::withToken($token)
            ->acceptJson()
            ->get($apiUrl);

        if ($response->failed()) {
            Log::error('Proxy to API failed', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['message' => 'Failed to fetch data from API service.'], 502);
        }

        return $response->json();
    }

    public function showReportDashboard()
    {
        return view('moderation.dashboard');
    }

    public function dashboard()
    {
        $data['title'] = 'dashboard';
        return view('dashboard.index', $data);
    }
}
