<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AgentCSVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = database_path('seeders/agents.csv');

        $csv = array_map('str_getcsv', file($csvFile));

        $headers = $csv[0];

        array_shift($csv);

        foreach ($csv as $row) {
            dd($row);
            $data = array_combine($headers, $row);
            YourModelName::create($data);
        }
    }
}
