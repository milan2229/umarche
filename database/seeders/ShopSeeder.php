<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shops')->insert([
            [
            'owner_id' => 1,
            'name' => '店名1',
            'information' => 'お店情報',
            'filename' => '2021/01/01 11:11:11',
            'is_selling' => true
            ],
            [
            'owner_id' => 2,
            'name' => '店名2',
            'information' => 'お店情報',
            'filename' => '2021/01/01 11:11:11',
            'is_selling' => true
            ],
        ]);
    }
}
