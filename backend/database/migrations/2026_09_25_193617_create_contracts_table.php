    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('contracts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
                $table->foreignId('company_id')->constrained()->cascadeOnDelete();

                $table->enum('contract_type', [
                    'indefinido',
                    'temporal',
                    'pasantia',
                    'medio_tiempo',
                    'tiempo_completo',
                ]);

                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->decimal('salary', 12, 2);
                $table->integer('working_hours')->default(40);
                $table->enum('status', ['active', 'expired', 'terminated'])->default('active');
                $table->text('notes')->nullable();

                $table->timestamps();

                $table->index(['employee_id', 'status']);
                $table->index('end_date');
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('contracts');
        }
    };