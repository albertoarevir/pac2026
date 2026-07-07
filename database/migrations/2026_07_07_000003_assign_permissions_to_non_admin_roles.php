<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        // Permisos que todos los roles no-admin necesitan para acceder al sistema
        $permisosBase = [
            'MENU PLAN ANUAL DE COMPRAS',
            'MENU DASHBOARD',
        ];

        // Permisos operacionales para roles no-admin
        $permisosOperacionales = [
            'INGRESAR PROYECTO',
            'MODIFICAR PROYECTO',
            'INGRESAR LICITACION',
            'MODIFICAR LICITACION',
            'INGRESAR ORDEN DE COMPRA',
            'MODIFICAR ORDEN DE COMPRA',
            'MENU LICITACIONES',
            'MENU ORDENES DE COMPRA',
            'MENU REPORTES',
        ];

        // Roles no-admin que deben tener acceso al sistema PAC
        $rolesNoAdmin = [
            'DIRECTOR',
            'ASESOR_TECNICO',
            'ASESOR TECNICO',   // por si fue creado con espacio
            'ALTA_REPARTICION',
            'JEFE_REPARTICION',
            'JEFE_REVISOR',
            'DIGITADOR',
            'USUARIO_BASICO',
        ];

        foreach ($rolesNoAdmin as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                continue;
            }

            foreach ($permisosBase as $perm) {
                $permission = Permission::where('name', $perm)->first();
                if ($permission && !$role->hasPermissionTo($perm)) {
                    $role->givePermissionTo($permission);
                }
            }

            // Roles operacionales (todos excepto USUARIO_BASICO básico)
            if ($roleName !== 'USUARIO_BASICO') {
                foreach ($permisosOperacionales as $perm) {
                    $permission = Permission::where('name', $perm)->first();
                    if ($permission && !$role->hasPermissionTo($perm)) {
                        $role->givePermissionTo($permission);
                    }
                }
            }
        }

        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        // No revertir: demasiado riesgo de quitar permisos necesarios
    }
};
