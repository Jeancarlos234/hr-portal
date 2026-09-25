# RRHH Sistema

Sistema profesional de Recursos Humanos para PyMEs, preparado para convertirse en SaaS.

## Stack

- **Backend**: Laravel 11 + PHP 8.2 + MySQL + Sanctum
- **Frontend**: React 18 + Vite + TypeScript + CSS Modules
- **Auth**: Laravel Sanctum (tokens)
- **Estado servidor**: TanStack Query
- **Formularios**: React Hook Form + Zod

## Estructura

\`\`\`
rrhh-sistema/
├── backend/     # API Laravel
└── frontend/    # SPA React
\`\`\`

## Requisitos

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL >= 8.0
- Git

## Instalación

### Backend
\`\`\`powershell
cd backend
composer install
Copy-Item .env.example .env
php artisan key:generate
# Configurar .env con credenciales MySQL
php artisan migrate
php artisan serve
\`\`\`

### Frontend
\`\`\`powershell
cd frontend
npm install
# Crear .env con VITE_API_URL
npm run dev
\`\`\`

## Variables de Entorno

### Backend (.env)
- APP_URL=http://localhost:8000
- FRONTEND_URL=http://localhost:5173
- DB_* credenciales MySQL
- SANCTUM_STATEFUL_DOMAINS=localhost:5173

### Frontend (.env)
- VITE_API_URL=http://localhost:8000/api

## Fases del Proyecto

- [x] Fase 1: Estructura base y configuración
- [ ] Fase 2: Auth, roles, permisos, empresas
- [ ] Fase 3: Empleados, departamentos, cargos, contratos
- [ ] Fase 4: Asistencia, vacaciones, permisos
- [ ] Fase 5: Documentos, evaluaciones, nómina
- [ ] Fase 6: Dashboard, reportes, notificaciones, auditoría
- [ ] Fase 7: Optimización, testing, producción