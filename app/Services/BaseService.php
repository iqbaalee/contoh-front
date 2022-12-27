<?php

namespace App\Services;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class BaseService
{
    public function apiGet(string $url, array $data = [], string $return)
    {
        if ($return == "json") {
            return Http::withHeaders(['Authorization' => 'Bearer ' . Cookie::get('X-FOOTSAL')])->get(env('REST_API') . $url, $data)->json();
        } else {
            return Http::withHeaders(['Authorization' => 'Bearer ' . Cookie::get('X-FOOTSAL')])->get(env('REST_API') . $url, $data)->body();
        }
    }

    public function apiPost(string $url, array $data = [], array $attach = [])
    {
        return Http::withHeaders(['Authorization' => 'Bearer ' . Cookie::get('X-FOOTSAL')])->attach($attach)->post(env('REST_API') . $url, $data)->body();
    }
    public function apiPut(string $url, array $data = [], array $attach = [])
    {
        return Http::withHeaders(['Authorization' => 'Bearer ' . Cookie::get('X-FOOTSAL')])->attach($attach)->put(env('REST_API') . $url, $data)->body();
    }

    public function apiDelete(string $url)
    {
        return Http::withHeaders(['Authorization' => 'Bearer ' . Cookie::get('X-FOOTSAL')])->delete(env('REST_API') . $url)->body();
    }
}
