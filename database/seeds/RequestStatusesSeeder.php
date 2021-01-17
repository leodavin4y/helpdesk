<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('request_statuses')->insert([
            [
                'name' => 'Новая',
            ],
            [
                'name' => 'Назначен исполнитель',
            ],
            [
                'name' => 'В работе',
            ],
            [
                'name' => 'На проверке',
            ],
            [
                'name' => 'Проблема решена',
            ],
            [
                'name' => 'Закрыта',
            ],
        ]);
    }
}
