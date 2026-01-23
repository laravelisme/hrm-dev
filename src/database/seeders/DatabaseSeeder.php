<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MCompany;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'username' => 'test',
        //     'password' => Hash::make('password'),
        //     'user_token' => Hash::make('test@example.com')
        // ]);

        // $this->call([
        //     RolePermissionSeeder::class,
        //     UserRoleSeeder::class
        // ]);

        $totalRecords = 100000; // total data
        $batchSize = 5000;         // insert per batch
        $levels = ['HOLDING', 'COMPANY'];

        for ($i = 0; $i < $totalRecords; $i += $batchSize) {
            $companies = [];

            for ($j = 0; $j < $batchSize; $j++) {
                $companies[] = [
                    'company_name' => 'PT Takoma Cemerlang ' . ($i + $j + 1),
                    'level' => $levels[array_rand($levels)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            MCompany::insert($companies);

            // echo "Inserted " . min($i + $batchSize, $totalRecords) . " / $totalRecords companies\n";
        }
    }
}

