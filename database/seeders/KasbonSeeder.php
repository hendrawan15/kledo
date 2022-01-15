<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kasbon;
use App\Models\Pegawai;

class KasbonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $j = 200;
        for ($i = 0; $i < $j; $i++) {
            $start = strtotime(date("Y-m-d", strtotime('-2 months')));
            $end = strtotime(date("Y-m-d"));

            $rand = mt_rand($start, $end);
            $date = date("Y-m-d", $rand);

            $number = mt_rand(10000, 100000);
            $pegawai_id = mt_rand(1, 10);

            $response = Kasbon::insert([
                        'tanggal_diajukan' => $date,
                        'pegawai_id' => Pegawai::all()->random()->id,
                        'total_kasbon' => $number
            ]);

        }
    }
}
