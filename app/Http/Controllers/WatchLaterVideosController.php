<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Reminder;
use Illuminate\Http\Request;
use App\Services\GoogleTokenService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Exception\RequestException;

class WatchLaterVideosController extends Controller
{

    public function index(Request $request, GoogleTokenService $googleTokenService)
    {
        $user = Auth::user();

        try {
            // Get a valid Google access token for the authenticated user
            $token = $googleTokenService->getValidAccessToken($user);


            // Get user's playlists
            $playlists = Http::withToken($token)->get('https://www.googleapis.com/youtube/v3/playlists', [
                'part' => 'snippet,contentDetails',
                'mine' => 'true',
            ]);

            // This will throw an exception if request failed
            $playlists->throw();

            // Find "WatchList" playlist
            $watchlist = collect($playlists->json('items'))->firstWhere('snippet.title', 'WatchList');

            if (!$watchlist) {
                return Inertia::render('WatchLater', [
                    'youtubeData' => [],
                    'flash' => ['error' => 'WatchList Playlist not found. Create a playlist named "WatchList" in your YouTube account.'],
                ]);
            }

            // Fetch videos in that playlist
            $videosResponse = Http::withToken($token)->get('https://www.googleapis.com/youtube/v3/playlistItems', [
                'part' => 'snippet',
                'playlistId' => $watchlist['id'],
                'maxResults' => 50,
            ]);
            $videosResponse->throw();

            $youtubeData = $videosResponse->json('items') ?? [];

            return Inertia::render('WatchLater', [
                'youtubeData' => $youtubeData,
            ]);
        } catch (\Throwable $e) {

            return Inertia::render('WatchLater', [
                'youtubeData' => [],
                'flash' => [
                    'error' => 'Unable to load WatchLater videos: Please check your Internet '
                ],
            ]);
        };
    }

    /**
     * Remove a video from the user's MyWatchList playlist and from the app.
     */
    public function destroy(GoogleTokenService $googleTokenService, $playlistitemid)
    {
        $user = Auth::user();

        try {
            $token = $googleTokenService->getValidAccessToken($user);

            // Remove from YouTube playlist
            $response = Http::withToken($token)
                ->delete("https://www.googleapis.com/youtube/v3/playlistItems?id={$playlistitemid}");

            // This will throw an exception if request failed
            $response->throw();

            // Remove from reminders table if stored
            Reminder::where('user_id', $user->id)
                ->where('video_id', $playlistitemid)
                ->delete();

            return redirect()->back();
        } catch (\Throwable $e) {

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
