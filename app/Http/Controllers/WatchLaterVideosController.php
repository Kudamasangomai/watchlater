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

      $token = $googleTokenService->getValidAccessToken($user);
      
     // Get user's playlists
      $playlists = Http::withToken($token)->get('https://www.googleapis.com/youtube/v3/playlists', [
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
      $videosResponse = Http::withToken($token)->get('https://www.googleapis.com/youtube/v3/playlistItems', [
          'part' => 'snippet',
          'playlistId' => $myWatchLater['id'],
          'maxResults' => 20,
      ]);

      $youtubeData = $videosResponse->json('items') ?? [];

      return Inertia::render('WatchLater', [
          'youtubeData' => $youtubeData,
      ]);

      
    }
}
