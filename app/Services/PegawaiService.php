<?php

namespace App\Services;

use App\Repository\PegawaiRepository;
use Illuminate\Http\Request;
use App\Http\Resources\PegawaiResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use DB;

class PegawaiService {

    protected $pegawai;

    public function __construct(PegawaiRepository $pegawai) {
        $this->pegawai = $pegawai;
    }

    public function getData(Request $request) {
        $message = [
            'integer' => ':attribute harus berupa angka',
            'nullable'=> ':attribute berisi angka atau null'
        ];
        $validator = Validator::make($request->all(), [
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
        
        $data = $this->pegawai->getList($request);

        return [
            'total' => $this->pegawai->getTotal(),
            'page' => (int) $request->page,
            'data' => PegawaiResource::collection($data)
        ];
    }

    public function storeData(Request $request) {
        DB::beginTransaction();
        try {
            $message = [
                'alpha' => ':attribute hanya bisa berupa huruf',
                'required' => ':attribute tidak boleh kosong',
                'unique' => ':attribute sudah ada di database',
                'nama.max' => 'Nama maksimal 10 karakter',
                'date' => ':attribute harus berformat tanggal',
                'tanggal_masuk.before' => 'Tanggal masuk maksimal hari ini',
                'integer' => ':attribute harus berupa angka',
                'total_gaji.min' => 'Total gaji minimal 4000000',
                'total_gaji.max' => 'Total gaji maksimal 10000000',
            ];
            $validator = Validator::make($request->all(), [
                        'nama' => 'alpha|required|unique:m_pegawai|max:10',
                        'tanggal_masuk' => 'date|required|before:tomorrow',
                        'total_gaji' => 'integer|required|min:4000000|max:10000000'
                            ], $message);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $data = [
                    'result' => false,
                    'message' => $errors->all(),
                ];

                return $data;
            }

            $model = $this->pegawai->insert();
            $model->nama = $request->nama;
            $model->tanggal_masuk = date("Y-m-d", strtotime($request->tanggal_masuk));
            $model->total_gaji = str_replace(".", "", $request->total_gaji);
            if (!$model->save()) {
                DB::rollback();
                return [
                    'result' => false,
                    'message' => "Data gagal disimpan"
                ];
            }

            DB::commit();
            return [
                'result' => true,
                'data' => new PegawaiResource($model)
            ];
        } catch (Exception $ex) {
            DB::rollback();
            return [
                'result' => false,
                'message' => 'Terjadi kesalahan pada penyimpanan data. Mohon Hubungi admin'
            ];
        }
    }

}
