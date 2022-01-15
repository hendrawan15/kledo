<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
//use Faker\Generator as Faker;

class KasbonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
//        $faker = Faker\Factory::create();
        
        $start = strtotime(date("Y-m-d",strtotime('-2 months')));
        $end = strtotime(date("Y-m-d"));
        
        $rand = mt_rand($start, $end);
        $date = date("Y-m-d",$rand);
        
        $number = mt_rand(10000, 100000);
        $pegawai_id = mt_rand(1,10);
        
        return [
            'tanggal_diajukan'=>$date,
            'pegawai_id'=>$pegawai_id,
            'total_kasbon'=>$number
        ];
    }
}
