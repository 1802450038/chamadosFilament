<?php

namespace Database\Seeders;

use App\Models\Call;
use Geocoder\Model\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Call::truncate();
        $csvFile = fopen(base_path("database/data/CHAMADOS_DT.csv"), "r");
        $firsline = true;
        while (($data = fgetcsv($csvFile, 2500, ",")) !== false) {
            if (!$firsline) {
                Call::create([
                    "id" => $data[0],
                    "user_id" => $data[1],
                    "issue" => $data[2],
                    "request" => $data[3],
                    "scheduling" => $data[4],
                    "location_id" => $data[5],
                    "created_at" => $data[6],
                    "updated_at" => $data[7],
                    "status" => $data[8],
                ]);
            }
            $firsline = false;
        }
        fclose($csvFile);
    }
}
