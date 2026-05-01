<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class D1Database
{
    private $workerUrl;

    public function __construct()
    {
        $this->workerUrl = env('CLOUDFLARE_WORKER_URL');
    }

    public function getUsers()
    {
        $response = Http::get($this->workerUrl . '/users');
        return $response->json();
    }

    public function createUser($data)
    {
        $response = Http::post($this->workerUrl . '/users', $data);
        return $response->json();
    }
}