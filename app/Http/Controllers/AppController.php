<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AppController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show()
    {
        return view('components.app');
    }

    public function store(Request $request)
    {
        $file = $request->input('url');
        $response = Http::get($file);

        if ($response->header('content-type') == 'image/gif') {
            return redirect('/')->with('danger', 'YOUR LINK CONTAINS A GIF');
        } else {
            return redirect('/')->with('safe', 'It was not a gif!');
        }
    }
}
