<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NewsController extends Controller
{
    public function showNewsView(Request $request)
    {
        if(!Gate::allows('view-news'))
            abort(403);

        return view('news');
    }
}
