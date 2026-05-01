<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Konfigurasi warna per PIC — bisa dipindah ke config/hdpsb.php
     */
    private array $warnaPic = [
        'ANJAS'    => ['bg' => '#E8F5E9', 'font' => '#1B5E20'],
        'JHON'     => ['bg' => '#E3F2FD', 'font' => '#0D47A1'],
        'LINA'     => ['bg' => '#FCE4EC', 'font' => '#880E4F'],
        'NANDA'    => ['bg' => '#FFF8E1', 'font' => '#E65100'],
        'PUTRI'    => ['bg' => '#F3E5F5', 'font' => '#4A148C'],
        'TAUFIQ'   => ['bg' => '#E0F7FA', 'font' => '#006064'],
        'TIKA'     => ['bg' => '#FBE9E7', 'font' => '#BF360C'],
        'IRVAN'    => ['bg' => '#F1F8E9', 'font' => '#33691E'],
        'JULIARDI' => ['bg' => '#EDE7F6', 'font' => '#311B92'],
        'RITA'     => ['bg' => '#FFF3E0', 'font' => '#BF360C'],
    ];

    public function index(Request $request)
    {
        $user    = session('helpdesk_user');
        $picList = array_keys($this->warnaPic);

        return view('dashboard.index', [
            'user'     => $user,
            'warnaPic' => $this->warnaPic,
            'picList'  => $picList,
            'apiBase'  => config('hdpsb.api_base'),
        ]);
    }
}
