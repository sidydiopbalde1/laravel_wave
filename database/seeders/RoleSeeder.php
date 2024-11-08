<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Role; // Assurez-vous d'avoir le bon namespace

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['libelle' => 'client']);
        Role::create(['libelle' => 'distributeur']);
        Role::create(['libelle' => 'agent']);
    }
}
