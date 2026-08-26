<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            'Sidoarjo', 'Madura', 'Tuban Lamongan', 'Jember', 'Probolinggo', 
            'Surabaya', 'Malang', 'Madiun', 'Jombang', 'Kediri', 
            'Gresik Mojokerto', 'Tulungagung'
        ];

        foreach ($branches as $name) {
            Branch::create(['name' => $name]);
        }
    }
}
