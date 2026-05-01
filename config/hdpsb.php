<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudflare Worker API Base URL
    |--------------------------------------------------------------------------
    | URL ini dipakai oleh JavaScript di frontend untuk fetch data tiket.
    | Ganti dengan URL Worker kamu jika berubah.
    |
    */
    'api_base' => env('HDPSB_API_BASE', 'https://moban-api.irvanberkatvatmanzend.workers.dev'),
];
