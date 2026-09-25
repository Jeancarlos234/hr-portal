<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Lista de permisos agrupados por módulo.
     * Formato: 'slug' => ['name', 'description']
     */
    public function run(): void
    {
        $permissions = [
            // ============ EMPRESAS ============
            'ver-empresas'        => ['Ver empresas', 'Listar y ver detalle de empresas'],
            'crear-empresas'      => ['Crear empresas', 'Registrar nuevas empresas'],
            'editar-empresas'     => ['Editar empresas', 'Modificar datos de empresas'],
            'eliminar-empresas'   => ['Eliminar empresas', 'Eliminar empresas del sistema'],

            // ============ USUARIOS ============
            'ver-usuarios'        => ['Ver usuarios', 'Listar usuarios del sistema'],
            'crear-usuarios'      => ['Crear usuarios', 'Registrar nuevos usuarios'],
            'editar-usuarios'     => ['Editar usuarios', 'Modificar datos de usuarios'],
            'eliminar-usuarios'   => ['Eliminar usuarios', 'Eliminar usuarios'],

            // ============ EMPLEADOS ============
            'ver-empleados'       => ['Ver empleados', 'Listar y ver detalle de empleados'],
            'crear-empleados'     => ['Crear empleados', 'Registrar nuevos empleados'],
            'editar-empleados'    => ['Editar empleados', 'Modificar datos de empleados'],
            'eliminar-empleados'  => ['Eliminar empleados', 'Eliminar empleados'],

            // ============ DEPARTAMENTOS ============
            'ver-departamentos'    => ['Ver departamentos', 'Listar departamentos'],
            'gestionar-departamentos' => ['Gestionar departamentos', 'Crear, editar y eliminar departamentos'],

            // ============ CARGOS ============
            'ver-cargos'          => ['Ver cargos', 'Listar cargos'],
            'gestionar-cargos'    => ['Gestionar cargos', 'Crear, editar y eliminar cargos'],

            // ============ CONTRATOS ============
            'ver-contratos'       => ['Ver contratos', 'Listar y ver contratos'],
            'gestionar-contratos' => ['Gestionar contratos', 'Crear, editar y eliminar contratos'],

            // ============ ASISTENCIA ============
            'ver-asistencia'      => ['Ver asistencia', 'Consultar registros de asistencia'],
            'gestionar-asistencia' => ['Gestionar asistencia', 'Registrar entrada/salida, editar asistencia'],

            // ============ VACACIONES ============
            'ver-vacaciones'      => ['Ver vacaciones', 'Consultar solicitudes de vacaciones'],
            'solicitar-vacaciones' => ['Solicitar vacaciones', 'Crear solicitudes propias'],
            'aprobar-vacaciones'  => ['Aprobar vacaciones', 'Aprobar o rechazar solicitudes'],

            // ============ PERMISOS / LICENCIAS ============
            'ver-permisos'        => ['Ver permisos', 'Consultar solicitudes de permisos'],
            'solicitar-permisos'  => ['Solicitar permisos', 'Crear solicitudes propias'],
            'aprobar-permisos'    => ['Aprobar permisos', 'Aprobar o rechazar permisos'],

            // ============ DOCUMENTOS ============
            'ver-documentos'      => ['Ver documentos', 'Consultar documentos'],
            'gestionar-documentos' => ['Gestionar documentos', 'Subir, editar y eliminar documentos'],

            // ============ EVALUACIONES ============
            'ver-evaluaciones'    => ['Ver evaluaciones', 'Consultar evaluaciones de desempeño'],
            'gestionar-evaluaciones' => ['Gestionar evaluaciones', 'Crear y editar evaluaciones'],

            // ============ NÓMINA ============
            'ver-nomina'          => ['Ver nómina', 'Consultar nóminas'],
            'gestionar-nomina'    => ['Gestionar nómina', 'Crear y editar nóminas'],

            // ============ REPORTES ============
            'ver-reportes'        => ['Ver reportes', 'Acceder a reportes del sistema'],
            'exportar-reportes'   => ['Exportar reportes', 'Exportar reportes a CSV/Excel/PDF'],

            // ============ AUDITORÍA ============
            'ver-auditoria'       => ['Ver auditoría', 'Consultar logs de auditoría'],
        ];

        // Extraer módulo del slug: "ver-empleados" → módulo "empleados"
        // "aprobar-vacaciones" → módulo "vacaciones"
        foreach ($permissions as $slug => [$name, $description]) {
            $parts  = explode('-', $slug);
            $module = end($parts); // última palabra = módulo

            Permission::updateOrCreate(
                ['slug' => $slug],
                [
                    'name'        => $name,
                    'module'      => $module,
                    'description' => $description,
                ]
            );
        }

        $this->command->info('✅ Permisos creados: ' . count($permissions));
    }
}