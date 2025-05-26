<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
      

      
        return Inertia::render('Dashboard', [
              
        ]);
    }
}
