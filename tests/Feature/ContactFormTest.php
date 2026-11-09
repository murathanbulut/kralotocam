<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Service;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(ServiceSeeder::class);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test Kullanıcı',
            'phone' => '0500 000 00 00',
            'email' => 'test@example.com',
            'service_id' => Service::query()->where('slug', 'cam-filmi')->value('id'),
            'message' => 'Ön camda küçük bir taş izi var, onarım mümkün mü?',
            'kvkk' => '1',
            'website' => '',
            'form_token' => Crypt::encryptString((string) (time() - 10)),
        ], $overrides);
    }

    public function test_valid_submission_is_stored_and_user_sees_success(): void
    {
        Mail::fake();
        config(['mail.default' => 'log']);

        $this->from('/iletisim/')
            ->post('/iletisim', $this->payload())
            ->assertRedirect('http://localhost/iletisim/#iletisim-formu')
            ->assertSessionHas('contact_success');

        $message = ContactMessage::query()->sole();
        $this->assertSame('Test Kullanıcı', $message->name);
        $this->assertSame('Cam Filmi', $message->service_title);
        $this->assertTrue($message->kvkk_consent);
        $this->assertFalse($message->is_read);
        // SMTP yapılandırılmadığında e-posta gönderilmiş gibi davranılmaz
        $this->assertNull($message->mailed_at);
        Mail::assertNothingSent();
    }

    public function test_notification_mail_is_sent_when_mailer_is_configured(): void
    {
        Mail::fake();
        config(['mail.default' => 'smtp', 'mail.contact_to' => 'info@kralotocam.com']);

        $this->post('/iletisim', $this->payload())->assertSessionHas('contact_success');

        Mail::assertSent(ContactMessageReceived::class, fn ($mail) => $mail->hasTo('info@kralotocam.com'));
        $this->assertNotNull(ContactMessage::query()->sole()->mailed_at);
    }

    public function test_required_fields_and_kvkk_consent_are_validated(): void
    {
        $this->from('/iletisim/')
            ->post('/iletisim', $this->payload(['name' => '', 'phone' => 'abc', 'message' => 'kısa', 'kvkk' => null]))
            ->assertRedirect('/iletisim/')
            ->assertSessionHasErrors(['name', 'phone', 'message', 'kvkk']);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_honeypot_submissions_are_rejected(): void
    {
        $this->post('/iletisim', $this->payload(['website' => 'http://spam.example']))
            ->assertSessionHasErrors('website');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_too_fast_or_tampered_submissions_are_rejected(): void
    {
        $this->post('/iletisim', $this->payload(['form_token' => Crypt::encryptString((string) time())]))
            ->assertSessionHasErrors('form_token');

        $this->post('/iletisim', $this->payload(['form_token' => 'sahte']))
            ->assertSessionHasErrors('form_token');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_html_is_stripped_from_input(): void
    {
        $this->post('/iletisim', $this->payload(['message' => '<script>alert(1)</script>Merhaba, bilgi almak istiyorum.']));

        $this->assertSame('alert(1)Merhaba, bilgi almak istiyorum.', ContactMessage::query()->sole()->message);
    }

    public function test_submissions_are_rate_limited(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post('/iletisim', $this->payload())->assertSessionHas('contact_success');
        }

        $this->post('/iletisim', $this->payload())->assertStatus(429);
        $this->assertSame(3, ContactMessage::count());
    }
}
