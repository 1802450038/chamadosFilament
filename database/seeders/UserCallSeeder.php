<?php

namespace Database\Seeders;

use App\Models\userCall;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // userCall::truncate();
        // DB::table('user_call')->truncate();
        $csvFile = fopen(base_path("database/data/USER_CALL_ID_3.csv"), "r");
        $firsline = true;
        while (($data = fgetcsv($csvFile, 3500, ",")) !== false) {
            if (!$firsline) {
                userCall::create([
                    "call_id" => $data[0],
                    "user_id" => $data[1],
                    "created_at" => $data[2],
                    "updated_at" => $data[3],
                ]);
            }
            $firsline = false;
        }
        fclose($csvFile);
        print("END CALL 3");
    }
}
