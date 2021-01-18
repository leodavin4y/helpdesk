<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('priorities')->insert([
            [
                'name' => 'Нормальный',
            ],
            [
                'name' => 'Средний',
            ],
            [
                'name' => 'Высокий',
            ],
        ]);
    }
}
