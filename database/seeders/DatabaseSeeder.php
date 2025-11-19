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
        $this->call([
            RoleSeeder::class,
        ]);
        
        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@lomas-padel.com',
            'password'=>'2019601919jJ+',
            'phone' => '5514127508'
        ]);

        $admin->assignRole('admin');
    }
}
