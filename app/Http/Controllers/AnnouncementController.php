<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    public function index()
    {
        $apiUrl = env('API_URL') . '/admin/announcements';
        $response = Http::withToken(session('token'))->get($apiUrl);

        $announcements = [];
        if ($response->successful()) {
            $announcements = $response->json();
            $announcements = $announcements['data'];
            Log::info('Successfully fetched announcements.');
        } else {
            Log::error('Failed to fetch announcements from API.', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
        }
        // dd($announcements);
        return view('platform.announcements.index', compact('announcements'));
    }


    public function create()
    {
        return view('platform.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'detail' => 'required|string',
        ]);

        $payload = [
            'title' => $request->input('title'),
            'detail' => $request->input('detail'),
            'status' => $request->input('status', 'draft'),
            'display_on_web' => $request->boolean('display_on_web'),
            'send_email' => $request->boolean('send_email'),
        ];

        Log::info('Attempting to store new announcement.', ['payload' => $payload]);
        $apiUrl = env('API_URL') . '/admin/announcements';
        $response = Http::withToken(session('token'))->post($apiUrl, $payload);

        if ($request->wantsJson()) {
            if ($response->successful()) {
                Log::info('AJAX Store successful.', ['response' => $response->json()]);
                $message = $response->json()['message'] ?? 'Pengumuman berhasil dibuat!';
                return response()->json(['success' => true, 'message' => $message]);
            } else {
                Log::error('AJAX Store failed.', ['status' => $response->status(), 'body' => $response->body()]);
                $errorData = $response->json();
                $message = $errorData['message'] ?? 'Gagal membuat pengumuman.';
                if (isset($errorData['errors'])) {
                    $message = implode(' ', array_values($errorData['errors'])[0]);
                }
                return response()->json(['success' => false, 'message' => $message], $response->status());
            }
        }

        if ($response->successful()) {
            Log::info('Announcement created successfully via API.', ['response' => $response->json()]);
            return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dibuat!');
        }

        Log::error('Failed to store announcement via API.', [
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        return back()->with('error', 'Gagal membuat pengumuman. Coba lagi.')->withInput();
    }

    public function edit($id)
    {
        $apiUrl = env('API_URL') . '/admin/announcements/' . $id;

        $response = Http::withToken(session('token'))->get($apiUrl);

        if ($response->successful()) {
            $announcement = $response->json();
            $announcement = $announcement['data'];
            Log::info('Successfully fetched announcement for editing.', ['id' => $id]);
            return view('platform.announcements.edit', compact('announcement'));
        }

        Log::error('Failed to fetch announcement for editing.', [
            'id' => $id,
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        return redirect()->route('admin.announcements.index')->with('error', 'Pengumuman tidak ditemukan.');
    }

    public function update(Request $request, $id)
    {
        $payload = [
            'title' => $request->input('title'),
            'detail' => $request->input('detail'),
            'status' => $request->input('status'),
            'display_on_web' => $request->boolean('display_on_web'),
            'send_email' => $request->boolean('send_email'),
        ];

        Log::info('Attempting to update announcement.', ['id' => $id, 'payload' => $payload]);

        $apiUrl = env('API_URL') . '/admin/announcements/' . $id;
        $response = Http::withToken(session('token'))->put($apiUrl, $payload);

        if ($request->wantsJson()) {
            if ($response->successful()) {
                Log::info('AJAX Update successful.', ['id' => $id]);
                return response()->json(['success' => true, 'message' => 'Pengumuman berhasil diperbarui!']);
            } else {
                Log::error('AJAX Update failed.', ['id' => $id, 'status' => $response->status()]);
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui pengumuman.'], $response->status());
            }
        }

        if ($response->successful()) {
            Log::info('Announcement updated successfully via API.', ['id' => $id]);
            return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
        }

        Log::error('Failed to update announcement via API.', [
            'id' => $id,
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        return back()->with('error', 'Gagal memperbarui pengumuman.')->withInput();
    }


    public function destroy(Request $request, $id)
    {
        Log::info('Attempting to delete announcement.', ['id' => $id]);
        $apiUrl = env('API_URL') . '/admin/announcements/' . $id;
        $response = Http::withToken(session('token'))->delete($apiUrl);

        if ($request->wantsJson()) {
            if ($response->successful()) {
                Log::info('AJAX Delete successful.', ['id' => $id]);
                return response()->json(['success' => true, 'message' => 'Pengumuman berhasil dihapus!']);
            } else {
                Log::error('AJAX Delete failed.', ['id' => $id, 'status' => $response->status()]);
                return response()->json(['success' => false, 'message' => 'Gagal menghapus pengumuman.'], $response->status());
            }
        }

        if ($response->successful()) {
            Log::info('Announcement deleted successfully via API.', ['id' => $id]);
            return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus!');
        }

        Log::error('Failed to delete announcement via API.', [
            'id' => $id,
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        return back()->with('error', 'Gagal menghapus pengumuman.');
    }
}
