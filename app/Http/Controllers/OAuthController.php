<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function redirect(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));
        $query = http_build_query([
            'client_id' => '3',
            'redirect_uri' => 'http://client.test:8000/oauth/callback',
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);

        return redirect('http://server.test/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class
        );

        $response = Http::asForm()->post('http://server.test/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => '3',
            'client_secret' => 'WUyXfmPXb06yFoRPD81pLii6N6RpRbTNQcGGMTes',
            'redirect_uri' => 'http://client.test:8000/oauth/callback',
            'code' => $request->code,
        ]);

        dd($response->json());

        return $response->json();
    }

    public function user(Request $request)
    {
        $accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiZWEwMWUzY2MxNGI0MTgzZWFkMDMyMzhiZGYzNjYxYmU4ZWFlMTAwMWZjYzZkMGE1ZjE3Nzk4NWRlYmNiNjRlNWI0MmQ2N2Y5MTVmYzVjYjgiLCJpYXQiOjE2MTIzMTgzNjYsIm5iZiI6MTYxMjMxODM2NiwiZXhwIjoxNjQzODU0MzY2LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.AuPCxV_tgo_ta4M34mmhUE8Oj1zyAlbIf4wRAebhjOCZTNyupzoscrqnQ8PuCmvtc5yutnf7UtJXzcZwhxwVk9RL9aiOg3SMKu9v0H7zrtR7bEmZ5NVOlYmK-QBAuXj9V9Gat04v2VVNyaw2_fLdJnMVR3ll85gIIeWVqORkVILqhRjn7x3eQki7IraXsytEEJGSmlEG9f0BOEXCGpC6vqFCbRLcRlLGUvKyD3ywB4xDdUwHyAZHOfhTpz1Cx-Eyjr77I_RQAz89KL68_fQ_SDGkY1wtkPx2WIenjwzE2OW7c07QCjR3-xwUMTdxJ7GTOtaFyfcZm3IUhitzwQP9VPC2v6EEcnGeVatMjPuq8oyYt-XsR-PwStU2BAztU5woz6XrQ_XtgnraO39s31USLWmKi3xkbpBjAky9UWEGLqZQsL9Iy5qP_rx3jPc_8Hfnsa7EoiLnZr5e6DWlhBGsBcDT5MNB7nhwPnF7bsRTmWopmV079xGDY911lI0hJFnxUNOjoOzYTcPfKb3v8OBHq0y3OZdfjpbWUYCi5J59lPIjuW_FY191R6vCk074NKx1sxMHprhzoDo4EpxmXyfN7dlybMBUdwrXngWjevibCpbtHZOyL5vfpaWvOl-2jc4xlqcEjKFL_XFQy-0IIY3Fd_Kf3gBHu0V5KzEoCVn45s0';
        // Http::get('http://server.test/api/user', [
        //     header()
        // ]);
        $res = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('http://server.test/api/user');
        dd($res->json());
        // return $response->json();
    }

}
