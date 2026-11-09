<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Service;
use App\Support\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        $seo = Seo::make(
            'İletişim | Kral Oto Cam Eskişehir',
            'Kral Oto Cam iletişim: Teksan Sanayi Sitesi, A4 Blok No: 1, Odunpazarı / Eskişehir. GSM / WhatsApp: '.setting('phone_gsm').'.'
        )->breadcrumbs([['İletişim']])->withLocalBusiness();

        return view('contact', [
            'seo' => $seo,
            'services' => Service::query()->active()->ordered()->get(['id', 'title', 'menu_title', 'slug']),
            'formToken' => ContactRequest::token(),
            'selectedService' => request()->query('hizmet'),
        ]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $service = isset($data['service_id']) ? Service::find($data['service_id']) : null;

        $message = ContactMessage::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'service_id' => $service?->id,
            'service_title' => $service?->title,
            'message' => $data['message'],
            'kvkk_consent' => true,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);

        // Gerçek bir posta sunucusu yapılandırılmadıysa e-posta gönderilmiş gibi davranılmaz.
        if ($this->mailIsConfigured()) {
            try {
                Mail::to(config('mail.contact_to'))->send(new ContactMessageReceived($message));
                $message->forceFill(['mailed_at' => now()])->save();
            } catch (\Throwable $e) {
                Log::warning('İletişim formu bildirimi gönderilemedi.', ['id' => $message->id, 'error' => $e->getMessage()]);
            }
        }

        return redirect()->to(site_url('iletisim').'#iletisim-formu')
            ->with('contact_success', 'Mesajınız bize ulaştı. En kısa sürede sizinle iletişime geçeceğiz. Acil durumlarda '.setting('phone_gsm').' numarasından bize ulaşabilirsiniz.');
    }

    private function mailIsConfigured(): bool
    {
        return ! in_array(config('mail.default'), ['log', 'array', null], true)
            && filled(config('mail.contact_to'));
    }
}
