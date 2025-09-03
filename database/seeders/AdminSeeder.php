<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Criar o usuário super admin
        $user = User::firstOrCreate(
            [
                'email' => 'admin@admin.com',
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Atribuir role de super_admin
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $user->assignRole($superAdminRole);

        $this->command->info('Super Admin criado com sucesso!');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Senha: password');
    }
}
