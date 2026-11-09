<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateAdminUser extends Command
{
    protected $signature = 'kral:admin {--email= : Yönetici e-posta adresi} {--name= : Ad soyad}';

    protected $description = 'Yönetim paneli (/admin) için yönetici hesabı oluşturur veya parolasını günceller.';

    public function handle(): int
    {
        $email = $this->option('email') ?: text('E-posta adresi', required: true);
        $name = $this->option('name') ?: text('Ad soyad', default: 'Yönetici', required: true);
        $password = password('Parola (en az 10 karakter)', required: true);
        $confirm = password('Parola (tekrar)', required: true);

        $validator = Validator::make(
            ['email' => $email, 'password' => $password, 'password_confirmation' => $confirm],
            ['email' => ['required', 'email'], 'password' => ['required', 'confirmed', Password::min(10)->letters()->numbers()]],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => $password, 'is_admin' => true],
        );

        $this->info(($user->wasRecentlyCreated ? 'Yönetici oluşturuldu: ' : 'Yönetici güncellendi: ').$email);

        return self::SUCCESS;
    }
}
