<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;


class HomeController extends Controller
{
    private const CACHE = true;

    public function index()
    {
        if (self::CACHE) {
            $response = Cache::remember('key_now_playing', 60, function () {
                return Http::withHeaders([
                    'Authorization' =>  'Bearer ' . env('TMDB_ACCESS_TOKEN'),
                    'accept'        => 'application/json'
                ])->get('https://api.themoviedb.org/3/movie/now_playing', [
                    'language'      => 'en-US',
                    'page'          => 1
                ])->json();
            });
        } else {
            $response = Http::withHeaders([
                'Authorization' =>  'Bearer ' . env('TMDB_ACCESS_TOKEN'),
                'accept'        => 'application/json'
            ])->get('https://api.themoviedb.org/3/movie/now_playing', [
                'language'      => 'en-US',
                'page'          => 1
            ])->json();
        }

        return view('welcome', compact('response'));
    }
}
