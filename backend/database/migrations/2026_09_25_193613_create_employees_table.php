    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained()->cascadeOnDelete();
                $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete();

                // Código único por empresa
                $table->string('code');

                // Info personal
                $table->string('first_name');
                $table->string('last_name');
                $table->string('document')->nullable();
                $table->date('birth_date')->nullable();
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
                $table->string('phone')->nullable();
                $table->string('mobile')->nullable();
                $table->string('email')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->default('Ecuador');

                // Contacto de emergencia
                $table->string('emergency_name')->nullable();
                $table->string('emergency_phone')->nullable();
                $table->string('emergency_relationship')->nullable();

                // Info laboral
                $table->date('hire_date');
                $table->date('termination_date')->nullable();
                $table->enum('contract_type', [
                    'indefinido',
                    'temporal',
                    'pasantia',
                    'medio_tiempo',
                    'tiempo_completo',
                ])->default('indefinido');
                $table->enum('workday', ['full', 'part', 'rotative'])->default('full');
                $table->decimal('salary', 12, 2)->nullable();
                $table->enum('status', ['active', 'suspended', 'vacation', 'terminated'])
                    ->default('active');

                $table->timestamps();
                $table->softDeletes();

                $table->unique(['company_id', 'code']);
                $table->index(['company_id', 'status']);
                $table->index('document');
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('employees');
        }
    };