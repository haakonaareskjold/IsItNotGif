<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AppController extends Controller
{
    /**
     * @var Response
     */
    public Response $response;

    public function validateForm(Request $request): array
    {
        return $request->validate([
            'url' => 'required|url|max:255',
        ]);
    }

    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show()
    {
        return view('components.app');
    }

    public function store(Request $request)
    {
        // Validates forms and assigning request to $file var
        $this->validateForm($request);
        $file = $request->input('url');

        // Tries to do a http request and assigns it to the property $response which uses the input, catches error
        try {
            $this->response = Http::get($file);
            if ($this->response->failed()) {
                $this->response->throw();
            }
        } catch (RequestException $exception) {
            abort('500', $exception);
        }

        // Request will flash the URL submitted as long as the http request has happened, despite the outcome.
        $request->session()->flash('link', "$file");

        // Checks if methods are true and then redirects with message it is animated, else its safe, see methods below
        if (
            $this->checkHttpHeader() == true || $this->checkHttpBody() == true || $this->identifyApng($file)
        ) {
            return redirect('/')->with('danger', 'CAREFUL, it is animated');
        } else {
            return redirect('/')->with('safe', 'it is safe!');
        }
    }

    // Checks if response body from http GET request contains str of either "gif" or "mp4"
    public function checkHttpBody(): bool
    {
        if (str_contains($this->response->body(), 'gif') || str_contains($this->response->body(), 'mp4')) {
            return true;
        } else {
            return false;
        }
    }

    // Checks if header has returned with MIME-types of image/gif or video/mp4
    public function checkHttpHeader(): bool
    {
        if (
            $this->response->header('Content-Type') == 'image/gif' ||
            $this->response->header('Content-Type') == 'video/mp4'
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @link https://stackoverflow.com/a/52687950
     * @param $filepath
     * @return bool
     */
    // Checks if $file submitted has binary string sequence matching for APNG
    public function identifyApng($filepath): bool
    {
        $apng = false;

        $fh = fopen($filepath, 'r');
        $previousdata = '';
        while (! feof($fh)) {
            $data = fread($fh, 1024);
            if (str_contains($data, 'acTL')) {
                $apng = true;
                break;
            } elseif (str_contains($previousdata . $data, 'acTL')) {
                $apng = true;
                break;
            } elseif (str_contains($data, 'IDAT')) {
                break;
            } elseif (str_contains($previousdata . $data, 'IDAT')) {
                break;
            }

            $previousdata = $data;
        }

        fclose($fh);

        return $apng;
    }
}
