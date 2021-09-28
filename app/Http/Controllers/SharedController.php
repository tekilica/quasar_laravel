<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Soundtrack;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class SharedController extends Controller
{
    public function showHomeView(Request $request)
    {
        $users = User::all()->sortBy(function($user)
        {
            return strtotime($user->created_at);
        }, SORT_REGULAR, true)->take(5);

        $images = Image::all()->sortBy(function($image) {
            return strtotime($image->created_at);
        }, SORT_REGULAR, true)->take(6);

        $videos = Video::all()->sortBy(function($video) {
            return strtotime($video->created_at);
        }, SORT_REGULAR, true)->take(6);

        $soundtracks = Soundtrack::all()->sortBy(function($soundtrack) {
            return strtotime($soundtrack->created_at);
        }, SORT_REGULAR, true)->take(5);

        return view('home', [
            'users' => $users,
            'images' => $images,
            'videos' => $videos,
            'soundtracks' => $soundtracks,
        ]);
    }
}
