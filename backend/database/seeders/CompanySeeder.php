<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::updateOrCreate(
            ['tax_id' => '1799999999001'],
            [
                'name'       => 'Empresa Demo S.A.',
                'legal_name' => 'Empresa Demo Sociedad Anónima',
                'email'      => 'contacto@empresademo.com',
                'phone'      => '+593 2 123 4567',
                'address'    => 'Av. Amazonas N34-123 y Av. Naciones Unidas',
                'city'       => 'Quito',
                'country'    => 'Ecuador',
                'website'    => 'https://empresademo.com',
                'status'     => 'active',
            ]
        );

        $this->command->info('✅ Empresa demo creada');
    }
}