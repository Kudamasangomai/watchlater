<?php

namespace App\Http\Controllers;

use App\Services\GoogleTokenService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

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
                'flash' => ['error' => 'Unable to load WatchLater videos: Please check your Internet  ' 
            ],
            ]);
        };
    }

    /**
     * Remove a video from the user's MyWatchLater playlist and from the app.
     */
    // public function removeVideo(Request $request, GoogleTokenService $googleTokenService)
    // {
    //     $user = Auth::user();
    //     $videoId = $request->input('videoId');
    //     $playlistItemId = $request->input('playlistItemId'); // You must pass this from the frontend

    //     try {
    //         $token = $googleTokenService->getValidAccessToken($user);

    //         // Remove from YouTube playlist
    //         $response = Http::withToken($token)
    //             ->delete('https://www.googleapis.com/youtube/v3/playlistItems', [
    //                 'id' => $playlistItemId,
    //             ]);
    //         if ($response->status() !== 204 && $response->status() !== 200) {
    //             throw new \Exception('Failed to remove video from YouTube playlist.');
    //         }

    //         // Optionally, remove from your app's reminders if you store them
    //         \App\Models\Reminder::where('user_id', $user->id)
    //             ->where('url', 'like', "%$videoId%")
    //             ->delete();

    //         return response()->json(['success' => true]);
    //     } catch (\Throwable $e) {
      
    //         return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    //     }
    // }
}
