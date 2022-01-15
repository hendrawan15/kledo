<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PegawaiResource extends JsonResource
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
            'nama'=> strtoupper(explode(" ", $this->nama)[0]),
            'tanggal_masuk'=>date("d/m/Y",strtotime($this->tanggal_masuk)),
            'total_gaji'=> number_format($this->total_gaji,0,'','.')
        ];
    }
}
