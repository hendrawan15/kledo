<?php

namespace App\Services;

use App\Repository\KasbonRepository;
use App\Repository\PegawaiRepository;
use Illuminate\Http\Request;
use App\Http\Resources\KasbonResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use DB;
use DateTime;
use Illuminate\Support\Facades\Log;
use App\Jobs\SetujuiMasalJob;

class KasbonService {

    protected $kasbon,$pegawai;

    public function __construct(KasbonRepository $kasbon,PegawaiRepository $pegawai) {
        $this->kasbon = $kasbon;
        $this->pegawai = $pegawai;
    }

    public function getData(Request $request) {
        $message = [
            'required' => ':attribute tidak boleh kosong',
            'bulan.date_format'=>"format bulan harus tahun-bulan",
            'integer' => ':attribute harus berupa angka',
            'nullable'=> ':attribute berisi angka atau null'
        ];
        $validator = Validator::make($request->all(), [
                    'bulan' => 'required|date_format:Y-m',
                    'belum_disetujui' => 'integer|nullable',
                    'page' => 'integer|nullable'
                        ], $message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $data = [
                'result' => false,
                'message' => $errors->all(),
            ];

            return $data;
        }

        $data = $this->kasbon->getList($request, 'data');

        return [
            'total' => $this->kasbon->getList($request, 'jumlah'),
            'page' => (int) $request->page,
            'data' => KasbonResource::collection($data)
        ];
    }

    public function storeData(Request $request) {
        DB::beginTransaction();
        try {
            $message = [
                'required' => ':attribute tidak boleh kosong',
                'exists' => ':attribute tidak ada di database',
                'integer' => ':attribute harus berupa angka',
            ];
            $validator = Validator::make($request->all(), [
                        'pegawai_id' => [
                            'integer',
                            'required',
                            'exists:m_pegawai,id'
                        ],
                        'total_kasbon'=>[
                            'integer'
                        ]
                
                         ], $message);
            
            if ($validator->fails()) {
                $errors = $validator->errors();
                $data = [
                    'result' => false,
                    'message' => $errors->all(),
                ];
                DB::rollback();
                return $data;
            }
            
            $getData = $this->pegawai->showData($request->pegawai_id);
            $tglNow = new DateTime(date("Y-m-d"));
            $tglKerja = new DateTime(date("Y-m-d", strtotime($getData->tanggal_masuk)));
            $selisih = $tglKerja->diff($tglNow);
            $lamaKerja = (int) $selisih->format('%y');
            if($lamaKerja < 1){
                DB::rollback();
               return [
                    'result' => false,
                    'message' => "Masa kerja Kurang dari 1 tahun",
                ]; 
            }
            
            $getJumlahKasbon = $this->kasbon->kasbonPegawai($request->pegawai_id)
                                ->whereMonth('tanggal_diajukan',date('m'))
                                ->whereYear('tanggal_diajukan',date('Y'))
                                ->count();
            if($getJumlahKasbon >= 3){
                DB::rollback();
                return [
                    'result' => false,
                    'message' => "Pengajuan Kasbon sudah mencapai batas maksimal",
                ]; 
            }
            
            $getNominalKasbon = $this->kasbon->kasbonPegawai($request->pegawai_id)
                                ->whereMonth('tanggal_diajukan',date('m'))
                                ->whereYear('tanggal_diajukan',date('Y'))
                                ->sum('total_kasbon');
            $gaji = $getData->total_gaji;
            
            $batasPinjaman = $gaji*0.5;
            
            $jumlahPinjaman = $getNominalKasbon+ $request->total_kasbon;
            if($jumlahPinjaman > $batasPinjaman){
                DB::rollback();
                return [
                    'result' => false,
                    'message' => "Kasbon dalam 1 bulan tidak boleh melebihi 50% dari gaji",
                ];
            }
            
            $model = $this->kasbon->insert();
            $model->tanggal_diajukan = date("Y-m-d");
            $model->pegawai_id = $request->pegawai_id;
            $model->total_kasbon = $request->total_kasbon;

            if (!$model->save()) {
                DB::rollback();
                return [
                    'result' => false,
                    'message' => "Data gagal disimpan"
                ];
            }
//
            DB::commit();
            return [
                'result' => true,
                'data' => new KasbonResource($model)
            ];
        } catch (Exception $ex) {
            DB::rollback();
            return [
                'result' => false,
                'message' => 'Terjadi kesalahan pada penyimpanan data. Mohon Hubungi admin'
            ];
        }
    }
    
    public function setujui($id){
       DB::beginTransaction();
        try {
            if(is_null($id)){
                DB::rollback();
                return [
                   'result' => false,
                    'message' => "ID kasbon tidak boleh kosong", 
                ];
            }
            
            $getData = $this->kasbon->show($id);
            if(is_null($getData)){
                DB::rollback();
                return [
                   'result' => false,
                    'message' => "ID kasbon tidak ditemukan", 
                ];
            }
            if(!is_null($getData->tanggal_disetujui)){
                DB::rollback();
                return [
                   'result' => false,
                    'message' => "tanggal disetujui sudah terisi", 
                ];
            }
            
            $model = $getData;
            $model->tanggal_disetujui = date("Y-m-d");
            if (!$model->save()) {
                DB::rollback();
                return [
                    'result' => false,
                    'message' => "Data gagal diperbarui"
                ];
            }
            
            DB::commit();
            return [
                'result' => true,
                'data' => new KasbonResource($model)
            ];
        } catch (Exception $ex) {
            DB::rollback();
            return [
                'result' => false,
                'message' => 'Terjadi kesalahan pada penyimpanan data. Mohon Hubungi admin'
            ];
        } 
    }
    
    public function setujuiMasal(){
        $getJumlah = $this->kasbon->getKasbonMonth()->count();
        
        dispatch(new SetujuiMasalJob());
        return [
                'result' => true,
                'message' => $getJumlah." pengajuan yang akan disetujui"
            ];
    }
    
    public function getSetujuiMasal(){

        try {
            $data = $this->kasbon->getKasbonMonth()->update([
                'tanggal_disetujui'=>date("Y-m-d")
            ]);
            Log::info([
               'result'=>true,
               'message'=>"Kasbon berhasil disetujui"
            ]);
        } catch (Exception $ex) {
            Log::error([
                'result' => false,
                'message' => $ex->getMessage()
            ]);
            
        }  
    }

}
