<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            canadaCitiesSqlSeeder::class,
            RoleSeeder::class,
            PermissionTableSeeder::class,
            UserSeeder::class,
            MbllSeeder::class,
            BritishColumbiaSeeder::class,
            OcsSeeder::class,
            SaskatchwanSeeder::class,
            AlbertaSeeder::class,
            CarveOutSeeder::class,
        ]);
    }
}
