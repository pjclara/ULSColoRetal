<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // RUN THE ROLES AND PERMISSIONS SEEDER
        //$this->call(UtenteSeeder::class);
        //$this->call(RolesAndPermissionsSeeder::class);

        // os que exitesr na pasta Permissions
        foreach (glob(database_path('seeders/Permissions/*.php')) as $file) {
            $className = pathinfo($file, PATHINFO_FILENAME);
            $this->call("Database\\Seeders\\Permissions\\$className");
        }
    }
}
