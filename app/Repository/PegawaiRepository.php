<?php
namespace App\Repository;

use App\Models\Pegawai;
use Auth;
use Illuminate\Http\Request;

class PegawaiRepository{
    
    public function getList(Request $request){
        if(!is_null($request->page)){
            $dataTampil =10;
            $end = $request->page*$dataTampil;
            $start = $end - $dataTampil;
            
            return Pegawai::skip($start)->take($end)->get();
        }else{
            return Pegawai::get();
        }
    }
    
    public function getTotal(){
        return Pegawai::count();
    }


    public function insert(){
        $pegawai = new Pegawai();
        return $pegawai;
    }
    
    public function showData($id){
        $pegawai = Pegawai::find($id);
        return $pegawai;
    }
}


