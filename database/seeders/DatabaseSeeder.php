<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            SiteContentSeeder::class,
            CorporateContentSeeder::class,
            FaqSeeder::class,
            LegalPagesSeeder::class,
        ]);

        $this->seedAdmin();
    }

    /**
     * İlk yönetici hesabı yalnızca .env içindeki ADMIN_EMAIL / ADMIN_PASSWORD değerlerinden oluşturulur.
     * Kaynak kodda sabit kimlik bilgisi bulunmaz. Değerler boşsa hesap oluşturulmaz;
     * bu durumda "php artisan kral:admin" komutunu kullanın.
     */
    private function seedAdmin(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (blank($email) || blank($password)) {
            $this->command?->warn('ADMIN_EMAIL / ADMIN_PASSWORD tanımlı değil; yönetici oluşturulmadı. "php artisan kral:admin" komutunu kullanın.');

            return;
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => env('ADMIN_NAME') ?: 'Yönetici', 'password' => $password, 'is_admin' => true],
        );

        $this->command?->info("Yönetici hesabı hazır: {$email}");
    }
}
