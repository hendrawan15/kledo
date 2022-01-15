<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Kasbon;
use App\Models\Pegawai;
use Faker\Factory as Faker;

class KasbonPegawaiTest extends TestCase {

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function get_pegawai() {
        $response = $this->get(route('pegawai.index'), [
            'page' => 1
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function insert_pegawai() {
        $response = $this->post(route('pegawai.store'), [
            'nama' => "bagus her",
            'tanggal_masuk' => '2021-01-01',
            'total_gaji' => 2000000
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function get_kasbon() {
        $response = $this->get(route('kasbon.index'), [
            'page' => 1,
            'bulan' => "2022-01",
            'belum_disetujui' => 1
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function insert_kasbon() {
        $response = $this->post(route('kasbon.store'), [
            'tanggal_diajukan' => date("Y-m-d"),
            'pegawai_id' => 1,
            'total_kasbon' => 1000000
        ]);

        $response->assertStatus(200);
    }

}
