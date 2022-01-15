<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PegawaiService;

class PegawaiController extends Controller {

    public function index(Request $request, PegawaiService $pegawai) {
        $data = $pegawai->getData($request);

        return response()->json($data);
    }

    public function store(Request $request, PegawaiService $pegawai) {

        $store = $pegawai->storeData($request);

        return response()->json($store);
    }

}
