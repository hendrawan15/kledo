<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasbon extends Model
{
    use HasFactory;
    protected $table = 't_kasbon';
    protected $primaryKey = 'id';
    
    public function pegawai(){
        return $this->hasOne(Pegawai::class,'id','pegawai_id');
    }
}
