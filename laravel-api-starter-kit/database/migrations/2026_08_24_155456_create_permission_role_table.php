<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar campos personalizados
            $table->string('nombre')->after('id');
            $table->string('apellido')->after('nombre');
            $table->string('nombre_usuario')->unique()->after('apellido');
            $table->string('celular', 20)->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('remember_token');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            
            // Soft deletes
            $table->softDeletes();
            
            // Índices
            $table->index(['nombre', 'apellido']);
            $table->index('nombre_usuario');
            $table->index('is_active');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nombre',
                'apellido',
                'nombre_usuario',
                'celular',
                'is_active',
                'last_login_at',
                'last_login_ip',
                'deleted_at',
            ]);
            
            $table->dropIndex(['nombre', 'apellido']);
            $table->dropIndex(['nombre_usuario']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['email']);
        });
    }
};