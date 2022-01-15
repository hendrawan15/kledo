<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class PegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();
        
        $start = strtotime(date("Y-m-d",strtotime('-2 years')));
        $end = strtotime(date("Y-m-d"));
        
        $rand = mt_rand($start, $end);
        $date = date("Y-m-d",$rand);
        
        $number = mt_rand(10000, 100000);
        
//        $date = $dt->format("Y-m-d");
        return [
            'nama'=>substr($faker->name(), 0, 10),
            'tanggal_masuk'=>$date,
            'total_gaji'=>$number
        ];
    }
}
