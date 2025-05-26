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
    // protected $googleTokenService;

    // public function __construct(GoogleTokenService $googleTokenService)
    // {
    //     $this->googleTokenService = $googleTokenService;
    // }

    public function index(Request $request)
    {
      $user = Auth::user();

        if (!$user->token) {
            return response()->json(['error' => 'Missing Google token.'], 401);
        }

        try {

        // Get user's playlists
        $playlists = Http::withToken($user->token)->get('https://www.googleapis.com/youtube/v3/playlists', [
            'part' => 'snippet,contentDetails',
            'mine' => 'true',
        ]);

        // This will throw an exception if request failed
        $playlists->throw();

        // Find "MyWatchLater" playlist
        $myWatchLater = collect($playlists->json('items'))->firstWhere('snippet.title', 'MyWatchLater');


        if (!$myWatchLater) {
            return Redirect::back()->with('error', ' "Mywatchlater" Playlist  not found.');
        }

        // Fetch videos in that playlist
        $videosResponse = Http::withToken($user->token)->get('https://www.googleapis.com/youtube/v3/playlistItems', [
            'part' => 'snippet',
            'playlistId' => $myWatchLater['id'],
            'maxResults' => 20,
        ]);

        $youtubeData = $videosResponse->json('items') ?? [];

        return Inertia::render('WatchLater', [
            'youtubeData' => $youtubeData,
        ]);

        } catch  (RequestException $e)  {
             return Redirect::back()->with('error', 'Failed to connect to YouTube. Please check your internet connection or try again later.');

        }
    }
}
