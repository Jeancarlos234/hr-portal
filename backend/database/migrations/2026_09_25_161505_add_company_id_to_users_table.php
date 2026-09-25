    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('company_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('companies')
                    ->nullOnDelete();

                $table->string('phone')->nullable()->after('email');
                $table->string('avatar')->nullable()->after('phone');
                $table->enum('status', ['active', 'inactive'])
                    ->default('active')
                    ->after('avatar');

                $table->index('status');
            });
        }

        public function down(): void
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['company_id']);
                $table->dropColumn(['company_id', 'phone', 'avatar', 'status']);
            });
        }
    };