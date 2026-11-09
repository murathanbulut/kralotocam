<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    /** Formun doldurulması için beklenen en kısa süre (saniye). Botlara karşı zaman tuzağı. */
    public const MIN_SECONDS = 3;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim(strip_tags((string) $this->input('name'))),
            'phone' => trim(strip_tags((string) $this->input('phone'))),
            'email' => trim((string) $this->input('email')) ?: null,
            'message' => trim(strip_tags((string) $this->input('message'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9\s\+\(\)\-]{10,20}$/'],
            'email' => ['nullable', 'email:rfc', 'max:150'],
            'service_id' => ['nullable', 'integer', Rule::exists(Service::class, 'id')->where('is_active', true)],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'kvkk' => ['accepted'],
            // Bal küpü (honeypot) alanı: gerçek kullanıcılar görmez, boş kalmalıdır.
            'website' => ['present', 'max:0'],
            'form_token' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'ad soyad',
            'phone' => 'telefon',
            'email' => 'e-posta',
            'service_id' => 'hizmet',
            'message' => 'mesaj',
            'kvkk' => 'KVKK onayı',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Lütfen geçerli bir telefon numarası girin.',
            'kvkk.accepted' => 'Devam etmek için kişisel verilerin işlenmesine ilişkin onayı vermeniz gerekir.',
            'website.max' => 'Form gönderilemedi. Lütfen sayfayı yenileyip tekrar deneyin.',
            'website.present' => 'Form gönderilemedi. Lütfen sayfayı yenileyip tekrar deneyin.',
            'form_token.required' => 'Form gönderilemedi. Lütfen sayfayı yenileyip tekrar deneyin.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            try {
                $renderedAt = (int) Crypt::decryptString((string) $this->input('form_token'));
            } catch (\Throwable) {
                $validator->errors()->add('form_token', 'Form gönderilemedi. Lütfen sayfayı yenileyip tekrar deneyin.');

                return;
            }

            $elapsed = time() - $renderedAt;

            if ($elapsed < self::MIN_SECONDS || $elapsed > 60 * 60 * 6) {
                $validator->errors()->add('form_token', 'Form gönderilemedi. Lütfen sayfayı yenileyip tekrar deneyin.');
            }
        });
    }

    public static function token(): string
    {
        return Crypt::encryptString((string) time());
    }
}
