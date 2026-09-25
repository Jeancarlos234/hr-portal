<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Roles iniciales del sistema y los permisos que otorgan.
     */
    public function run(): void
    {
        // ==========================================
        // 1. SUPER ADMIN — acceso total
        // ==========================================
        $superAdmin = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name'        => 'Super Administrador',
                'description' => 'Control total del sistema. Puede administrar empresas y usuarios globales.',
                'is_system'   => true,
            ]
        );
        // Le damos TODOS los permisos
        $superAdmin->permissions()->sync(
            Permission::pluck('id')->toArray()
        );

        // ==========================================
        // 2. ADMINISTRADOR — administra su empresa
        // ==========================================
        $admin = Role::updateOrCreate(
            ['slug' => 'administrador'],
            [
                'name'        => 'Administrador de Empresa',
                'description' => 'Administra la empresa, usuarios y configuración general.',
                'is_system'   => true,
            ]
        );
        $admin->permissions()->sync(
            Permission::whereNotIn('slug', [
                'eliminar-empresas',
                'crear-empresas',
                'ver-auditoria',
            ])->pluck('id')->toArray()
        );

        // ==========================================
        // 3. RRHH — gestión de personal
        // ==========================================
        $rrhh = Role::updateOrCreate(
            ['slug' => 'rrhh'],
            [
                'name'        => 'Recursos Humanos',
                'description' => 'Gestión completa de empleados, asistencia, vacaciones y nómina.',
                'is_system'   => true,
            ]
        );
        $rrhh->permissions()->sync(
            Permission::whereIn('slug', [
                'ver-empleados', 'crear-empleados', 'editar-empleados',
                'ver-departamentos', 'gestionar-departamentos',
                'ver-cargos', 'gestionar-cargos',
                'ver-contratos', 'gestionar-contratos',
                'ver-asistencia', 'gestionar-asistencia',
                'ver-vacaciones', 'aprobar-vacaciones',
                'ver-permisos', 'aprobar-permisos',
                'ver-documentos', 'gestionar-documentos',
                'ver-evaluaciones', 'gestionar-evaluaciones',
                'ver-nomina', 'gestionar-nomina',
                'ver-reportes', 'exportar-reportes',
            ])->pluck('id')->toArray()
        );

        // ==========================================
        // 4. SUPERVISOR — supervisa su equipo
        // ==========================================
        $supervisor = Role::updateOrCreate(
            ['slug' => 'supervisor'],
            [
                'name'        => 'Supervisor',
                'description' => 'Supervisa a su equipo. Puede ver asistencia y aprobar permisos.',
                'is_system'   => true,
            ]
        );
        $supervisor->permissions()->sync(
            Permission::whereIn('slug', [
                'ver-empleados',
                'ver-asistencia', 'gestionar-asistencia',
                'ver-vacaciones', 'aprobar-vacaciones',
                'ver-permisos', 'aprobar-permisos',
                'ver-evaluaciones',
                'ver-reportes',
            ])->pluck('id')->toArray()
        );

        // ==========================================
        // 5. EMPLEADO — rol base
        // ==========================================
        $empleado = Role::updateOrCreate(
            ['slug' => 'empleado'],
            [
                'name'        => 'Empleado',
                'description' => 'Rol base. Puede ver su propia información y solicitar vacaciones/permisos.',
                'is_system'   => true,
            ]
        );
        $empleado->permissions()->sync(
            Permission::whereIn('slug', [
                'ver-asistencia',
                'ver-vacaciones', 'solicitar-vacaciones',
                'ver-permisos', 'solicitar-permisos',
                'ver-documentos',
                'ver-evaluaciones',
            ])->pluck('id')->toArray()
        );

        $this->command->info('✅ Roles creados: 5');
    }
}