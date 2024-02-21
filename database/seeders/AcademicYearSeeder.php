<?php

namespace Database\Seeders;

use App\Models\Academic_Year;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFilePath = database_path('seeds/sample_academic_years_seeder.csv');
        $csv = Reader::createFromPath($csvFilePath, 'r');
        $csv->setHeaderOffset(0);

        foreach($csv->getRecords() as $offset=>$record)
        {
            Academic_Year::create([
                'acad_year' => $record['acad_year'],
                'acad_year_start' => $record['acad_year_start'],
                'acad_year_end' => $record['acad_year_end'],
            ]);
        }
    }
}
