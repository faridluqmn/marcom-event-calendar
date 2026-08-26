<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Marcom;
use App\Models\Branch;
use App\Models\Brand;
use Faker\Factory as Faker;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            // Contoh event yang sudah selesai (result sudah ada)
            [
                'name' => 'Konser Kemerdekaan IM3',
                'location' => 'Alun-Alun Sidoarjo',
                'start_date' => date('Y-m-d', strtotime('-10 days')),
                'end_date' => date('Y-m-d', strtotime('-8 days')),
                'marcom_name' => 'HERLINA RATNANINGSIH',
                'estimation' => 150,
                'result' => 180,
                'is_regional' => true,
            ],
            // Contoh event yang akan datang (result masih kosong)
            [
                'name' => 'Bazaar Pelajar 3ID',
                'location' => 'SMAN 1 Sidoarjo',
                'start_date' => date('Y-m-d', strtotime('+5 days')),
                'end_date' => date('Y-m-d', strtotime('+7 days')),
                'marcom_name' => 'MUHAMMAD ABID HAEKAL',
                'estimation' => 200,
                'result' => null,
                'is_regional' => false,
            ],
            [
                'name' => 'Jalan Sehat IM3 Madura',
                'location' => 'Stadion Gelora Ratu Pamelingan',
                'start_date' => date('Y-m-d', strtotime('+12 days')),
                'end_date' => date('Y-m-d', strtotime('+12 days')),
                'marcom_name' => 'AHMAD BAIHAKI',
                'estimation' => 350,
                'result' => null,
                'is_regional' => true,
            ],
            [
                'name' => 'Turnamen E-Sports 3ID',
                'location' => 'Warkop Giras Pamekasan',
                'start_date' => date('Y-m-d', strtotime('+20 days')),
                'end_date' => date('Y-m-d', strtotime('+22 days')),
                'marcom_name' => 'ANDRI SETYAWAN',
                'estimation' => 120,
                'result' => null,
                'is_regional' => false,
            ],
        ];

        // Gunakan user pertama sebagai pembuat event
        $user = User::first();

        foreach ($events as $data) {
            // Cari marcom berdasarkan nama untuk mengambil branch & brand secara otomatis
            $marcom = Marcom::where('name', $data['marcom_name'])->first();

            if ($marcom && $user) {
                Event::create([
                    'name' => $data['name'],
                    'location' => $data['location'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'user_id' => $user->id,
                    'marcom_id' => $marcom->id,
                    'branch_id' => $marcom->branch_id,
                    'brand_id' => $marcom->brand_id,
                    'estimation' => $data['estimation'],
                    'result' => $data['result'],
                    'is_regional' => $data['is_regional'],
                ]);
            }
        }
    }
}