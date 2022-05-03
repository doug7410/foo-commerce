<?php

namespace Database\Seeders;


use DB;
use Illuminate\Database\Seeder;

class CsvSeeder extends Seeder
{

    /**
     * @param $file
     * @param string $delimiter
     * @param string $enclosure
     * @return array
     */
    public function csvToAssociativeArray($file, $delimiter = ',', $enclosure = '"')
    {
        if (($handle = fopen($file, "r")) !== false) {
            $headers = fgetcsv($handle, 0, $delimiter, $enclosure);
            $lines = [];
            while (($data = fgetcsv($handle, 0, $delimiter, $enclosure)) !== false) {
                $current = [];
                $i = 0;
                foreach ($headers as $header) {
                    $current[$header] = $data[$i++];
                }
                $lines[] = $current;
            }
            fclose($handle);
            return $lines;
        }
    }

    /**
     * @param string $file
     * @param string $table
     * @param callable $csvMap
     */
    protected function insertData(string $file, string $table, callable $csvMap) : void
    {
        $records = $this->csvToAssociativeArray($file);

        dump('Seeding ' . count($records) . ' records into ' . $table . ' table');

        collect($records)
            ->map($csvMap)
            ->chunk(500)
            ->each(function ($chunk) use ($table) {
                DB::table($table)->insert($chunk->toArray());
            });

        dump('Inserted ' . DB::table($table)->count() . ' records into ' . $table . ' table');
    }
}