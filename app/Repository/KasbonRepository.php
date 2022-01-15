<?php

namespace App\Repository;

use App\Models\Kasbon;
use Auth;
use Illuminate\Http\Request;

class KasbonRepository {

    public function getList(Request $request, $jenis) {
        $bulan = date("m", strtotime($request->bulan));
        $tahun = date("Y", strtotime($request->bulan));
        $data = Kasbon::whereMonth('tanggal_diajukan', $bulan)
                ->whereYear('tanggal_diajukan', $tahun);
        if ($request->belum_disetujui == 1) {
            $data->whereNull('tanggal_disetujui');
        }
        if ($jenis == "data") {
            if (!is_null($request->page)) {

                $dataTampil = 10;
                $end = $request->page * $dataTampil;
                $start = $end - $dataTampil;

                $data = $data->skip($start)->take($end)->get();
            } else {
                $data = $data->get();
            }
        }else{
            $data = $data->count();
        }

        return $data;
    }
    

    public function insert() {
        $kasbon = new Kasbon();
        return $kasbon;
    }
    
    public function show($id){
        return Kasbon::find($id);
    }
    
    public function kasbonPegawai($pegawai){
        return Kasbon::where('pegawai_id',$pegawai);
    }
    
    public function getKasbonMonth(){
        return Kasbon::whereMonth('tanggal_diajukan', date('m'))
                ->whereYear('tanggal_diajukan', date('Y'))
                ->whereNull('tanggal_disetujui');
    }

}


