<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use Faker\Factory as Faker;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $j = 10;

        for ($i = 0; $i < $j; $i++) {
            $faker = Faker::create();

            $start = strtotime(date("Y-m-d", strtotime('-2 years')));
            $end = strtotime(date("Y-m-d"));

            $rand = mt_rand($start, $end);
            $date = date("Y-m-d", $rand);

            $number = mt_rand(10000, 100000);

            $response = Pegawai::insert([
                        'nama' => substr($faker->name(), 0, 10),
                        'tanggal_masuk' => $date,
                        'total_gaji' => $number
            ]);
            
        }

    }
}
