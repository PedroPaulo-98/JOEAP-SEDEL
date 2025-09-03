<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Facades\Filament;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Support\Str;

class ShieldPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar todas as permissões do Filament Shield
        $this->createResourcePermissions();
        $this->createPagePermissions();
        $this->createWidgetPermissions();

        // Criar role de super_admin e atribuir todas as permissões
        $this->createSuperAdminRole();

        $this->command->info('Todas as permissões do Shield criadas com sucesso!');
    }

    protected function createResourcePermissions(): void
    {
        $resources = Filament::getResources();

        foreach ($resources as $resource) {
            $entity = $this->getEntityNameFromClass($resource);

            $permissions = [
                "view_{$entity}",
                "view_any_{$entity}",
                "create_{$entity}",
                "update_{$entity}",
                "delete_{$entity}",
                "delete_any_{$entity}",
                "restore_{$entity}",
                "restore_any_{$entity}",
                "replicate_{$entity}",
                "reorder_{$entity}",
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web'
                ]);
            }

            $this->command->info("Permissões criadas para resource: {$entity}");
        }
    }

    protected function createPagePermissions(): void
    {
        $pages = Filament::getPages();

        foreach ($pages as $page) {
            $entity = $this->getEntityNameFromClass($page);

            $permission = "view_{$entity}";

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);

            $this->command->info("Permissão criada para page: {$entity}");
        }
    }

    protected function createWidgetPermissions(): void
    {
        $widgets = Filament::getWidgets();

        foreach ($widgets as $widget) {
            // Widgets podem ser classes ou arrays de configuração
            $widgetClass = is_array($widget) ? ($widget['class'] ?? null) : $widget;

            if ($widgetClass && class_exists($widgetClass)) {
                $entity = $this->getEntityNameFromClass($widgetClass);

                $permission = "view_{$entity}";

                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web'
                ]);

                $this->command->info("Permissão criada para widget: {$entity}");
            }
        }
    }

    protected function getEntityNameFromClass(string $class): string
    {
        return Str::of($class)
            ->afterLast('\\')
            ->replace('Resource', '')
            ->replace('Page', '')
            ->replace('Widget', '')
            ->snake()
            ->toString();
    }

    protected function createSuperAdminRole(): void
    {
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web'
        ]);

        // Atribuir todas as permissões ao super_admin
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);

        $this->command->info('Role super_admin criada com todas as permissões!');
        $this->command->info('Total de permissões: ' . $allPermissions->count());
    }
}
