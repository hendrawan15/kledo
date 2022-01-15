<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KasbonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'tanggal_diajukan'=>date("d/m/Y",strtotime($this->tanggal_diajukan)),
            'tanggal_disetujui'=>(!is_null($this->tanggal_disetujui))?date("d/m/Y",strtotime($this->tanggal_disetujui)):null,
            'nama_pegawai'=>$this->pegawai->nama,
            'total_kasbon'=>number_format($this->total_kasbon,0,'','.')
        ];
    }
}
