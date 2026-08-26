<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Marcom;

class MarcomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Panduan ID:
        // brand_id: 1 = 3ID, 2 = IM3
        // branch_id: 1=Sidoarjo, 2=Madura, 3=Tuban Lamongan, 4=Jember, 5=Probolinggo, 
        //            6=Surabaya, 7=Malang, 8=Madiun, 9=Jombang, 10=Kediri, 11=Gresik Mojokerto, 12=Tulungagung

        $marcoms = [
            ['name' => 'HERLINA RATNANINGSIH', 'brand_id' => 2, 'branch_id' => 1], // IM3, Sidoarjo
            ['name' => 'AHMAD BAIHAKI',        'brand_id' => 2, 'branch_id' => 2], // IM3, Madura
            ['name' => 'ANDIK PRASTIO',        'brand_id' => 2, 'branch_id' => 3], // IM3, Tuban Lamongan
            ['name' => 'RIZAL RUL HAQ',        'brand_id' => 2, 'branch_id' => 4], // IM3, Jember
            ['name' => 'RENDY SUBROTO',        'brand_id' => 2, 'branch_id' => 5], // IM3, Probolinggo
            ['name' => 'VACANT',               'brand_id' => 2, 'branch_id' => 6], // IM3, Surabaya
            ['name' => 'CANDRA DEWI ASIH',     'brand_id' => 2, 'branch_id' => 4], // IM3, Jember
            ['name' => 'IMAM ARIF FENDI',      'brand_id' => 2, 'branch_id' => 7], // IM3, Malang
            ['name' => 'EKO BUDI',             'brand_id' => 2, 'branch_id' => 8], // IM3, Madiun
            ['name' => 'ACHMAD SYAIFUL ARIF',  'brand_id' => 2, 'branch_id' => 9], // IM3, Jombang
            ['name' => 'FELLA KUNTARYANTI',    'brand_id' => 2, 'branch_id' => 10], // IM3, Kediri
            ['name' => 'DHIKA ANANTA AKBAR',   'brand_id' => 2, 'branch_id' => 11], // IM3, Gresik Mojokerto
            ['name' => 'DHANY RENALDO',        'brand_id' => 2, 'branch_id' => 2], // IM3, Madura
            ['name' => 'VACANT',               'brand_id' => 2, 'branch_id' => 12], // IM3, Tulungagung

            ['name' => 'MUHAMMAD ABID HAEKAL', 'brand_id' => 1, 'branch_id' => 1], // 3ID, Sidoarjo
            ['name' => 'ANDRI SETYAWAN',       'brand_id' => 1, 'branch_id' => 7], // 3ID, Malang
            ['name' => 'CHRISNAWATI PAMUNGKAS','brand_id' => 1, 'branch_id' => 11], // 3ID, Gresik Mojokerto
            ['name' => 'FERRY HIDAYATULLOH',   'brand_id' => 1, 'branch_id' => 9], // 3ID, Jombang
            ['name' => 'MUKLAS ARIFIN',        'brand_id' => 1, 'branch_id' => 5], // 3ID, Probolinggo
            ['name' => 'RIBUT SUGIONO',        'brand_id' => 1, 'branch_id' => 3], // 3ID, Tuban Lamongan
            ['name' => 'ADE MAHENDRA',         'brand_id' => 1, 'branch_id' => 12], // 3ID, Tulungagung
            ['name' => 'HERU SUTOYO',          'brand_id' => 1, 'branch_id' => 2], // 3ID, Madura
            ['name' => 'DYAS TEGAR WIJAYA',    'brand_id' => 1, 'branch_id' => 10], // 3ID, Kediri
            ['name' => 'NOVAN FAUZAN',         'brand_id' => 1, 'branch_id' => 4], // 3ID, Jember
            ['name' => 'NUR M SHOBACH',        'brand_id' => 1, 'branch_id' => 6], // 3ID, Surabaya
            ['name' => 'TRIA ANDRIYANTO',      'brand_id' => 1, 'branch_id' => 8], // 3ID, Madiun
        ];

        foreach ($marcoms as $data) {
            Marcom::create($data);
        }
    }
}
