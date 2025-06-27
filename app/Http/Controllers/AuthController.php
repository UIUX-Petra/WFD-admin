<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        return view("login", [
            'title' => 'login',
        ]);
    }

    public function googleAuth()
    {
        return Socialite::driver('google')->redirect();
    }

    public function processLogin()
    {
        try {
            $user = Socialite::driver('google')->user();
            if (!$user) {
                Log::warning('Gagal mendapatkan informasi user dari Google.');
                return redirect()->route('admin.login')->with('error', 'Failed to get user information from Google.');
            }

            $email = strtolower($user->getEmail());
            $name = $user->getName();
            
            Log::info('Data user dari Google diterima:', ['email' => $email, 'name' => $name]);

            if (!str_ends_with($email, '@john.petra.ac.id')) {
                Log::warning('Percobaan login dengan domain tidak valid:', ['email' => $email]);
                return redirect()->route('admin.login')->with('error', 'Please use your Petra Christian University email to log in!');
            }

            $apiUrl = env('API_URL') . '/admin/auth/socialite';
            $payload = [
                'name' => $name,
                'email' => $email,
                'secret' => env('API_SECRET')
            ];

            Log::info('Mengirim permintaan login ke API:', ['url' => $apiUrl, 'payload' => $payload]);

            $response = Http::post($apiUrl, $payload);

            if ($response->failed()) {
                Log::error('API login gagal.', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                $errorMessage = $response->json('message', 'Login failed. Please try again.');
                return redirect()->route('admin.login')->with('error', $errorMessage);
            }

            $responseData = $response->json();
            Log::info('API login berhasil. Response data:', $responseData);

            if (!isset($responseData['data']['token'])) {
                Log::error('Struktur response dari API tidak valid.', ['response' => $responseData]);
                return redirect()->route('admin.login')->with('error', 'Invalid response from the login server.');
            }
            $storedUser = $responseData['data'];
            
            session([
                'id' => $storedUser['id'],
                'email' => $storedUser['email'],
                'name' => $storedUser['name'],
                'token' => $storedUser['token'],
                'roles' => $storedUser['roles']
            ]);
            
            Log::info('Session berhasil dibuat untuk user:', ['email' => $storedUser['email'], 'roles' => $storedUser['roles']]);

            $url = session('url');
            if ($url) {
                session()->forget('url');
                return redirect()->to($url);
            }
            return redirect()->route('admin.dashboard');

        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('admin.login')->with('error', 'An unexpected error occurred during login.');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('admin.login');
    }
}