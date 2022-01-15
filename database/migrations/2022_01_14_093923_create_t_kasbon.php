<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTKasbon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_kasbon', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_diajukan');
            $table->date('tanggal_disetujui')->nullable();
            $table->unsignedBigInteger('pegawai_id');
            $table->integer('total_kasbon');
            $table->timestamps();
            
            $table->foreign('pegawai_id')->references('id')->on('m_pegawai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_kasbon');
    }
}
