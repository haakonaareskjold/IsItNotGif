<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AppController extends Controller
{
    public function validateForm(Request $request): array
    {
        return $request->validate([
            'url' => 'required|url|max:255',
        ]);
    }

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
        $this->validateForm($request);
        $file = $request->input('url');

        try {
            $response = Http::get($file);
            if ($response->failed()) {
                $response->throw();
            }
        } catch (RequestException $exception) {
            abort('500', $exception);
        }

        $request->session()->flash('link', "$file");

        if (str_contains($response->body(), 'gif') || str_contains($response->body(), 'mp4')) {
            return redirect('/')->with('danger', 'CAREFUL, it is animated');
        } elseif ($response->header('content-type') == 'video/mp4' || $response->header('content-type' == 'image/gif')) {
            return redirect('/')->with('danger', 'CAREFUL, it is animated');
        } elseif ($this->identify_apng($file) == true) {
            return redirect('/')->with('danger', 'CAREFUL, it is animated');

        // idea from function at php.net - https://www.php.net/manual/en/function.imagecreatefromgif.php
        } elseif (is_string($file)) {
            $fp = fopen($file, 'rb');
        } else {
            $fp = $file;

            /* Make sure that we are at the beginning of the file */
            fseek($fp, 0);
        }

        if (fread($fp, 3) !== 'GIF') {
            fclose($fp);

            return redirect('/')->with('safe', 'it is NOT animated');
        }

        $frames = 0;

        while (! feof($fp) && $frames < 2) {
            if (fread($fp, 1) === "\x00") {
                /* Some of the animated GIFs do not contain graphic control extension (starts with 21 f9) */
                if (fread($fp, 1) === "\x21" || fread($fp, 2) === "\x21\xf9" || fread($fp, 2) === "\x21\x2c") {
                    $frames++;
                }
            }
        }

        fclose($fp);

        if ($frames > 1) {
            return redirect('/')->with('danger', 'CAREFUL, it is animated');
        }
    }

    /**
     * @link https://stackoverflow.com/a/52687950
     * @param $filepath
     * @return bool
     */
    public function identify_apng($filepath): bool
    {
        $apng = false;

        $fh = fopen($filepath, 'r');
        $previousdata = '';
        while (! feof($fh)) {
            $data = fread($fh, 1024);
            if (strpos($data, 'acTL') !== false) {
                $apng = true;
                break;
            } elseif (strpos($previousdata.$data, 'acTL') !== false) {
                $apng = true;
                break;
            } elseif (strpos($data, 'IDAT') !== false) {
                break;
            } elseif (strpos($previousdata.$data, 'IDAT') !== false) {
                break;
            }

            $previousdata = $data;
        }

        fclose($fh);

        return $apng;
    }
}
