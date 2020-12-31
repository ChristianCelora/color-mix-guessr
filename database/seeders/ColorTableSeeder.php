<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ColorTableSeeder extends Seeder
{
    const SEED_FILENAME = "colors_seed.csv";
    const FILE_SEPARATOR = ",";
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path('app/seeds/' . static::SEED_FILENAME);
        if(!file_exists($path)){
            new \Exception("file $path not existing");
        }
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($color = fgetcsv($handle, 1000, self::FILE_SEPARATOR)) !== FALSE) {
                if(count($color) >= 6){
                    $model = new \App\Models\Color();
                    $model->color_code = $color[0];
                    $model->name = $color[1];
                    $model->hex = $color[2];
                    $model->red = $color[3];
                    $model->blue = $color[4];
                    $model->green = $color[5];
                    $model->save();
                }
            }
        }
    }
}
