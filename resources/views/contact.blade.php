<x-layouts.app :seo="$seo">
    <x-page-heading title="İletişim" :seo="$seo" />

    <section class="kral-section kral-contact">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-sm-6 col-lg-3">
                    <div class="kral-contact-card">
                        <i class="bi bi-whatsapp" aria-hidden="true"></i>
                        <h2>GSM / WhatsApp</h2>
                        <a href="{{ phone_href(setting('phone_gsm')) }}">{{ setting('phone_gsm') }}</a>
                        <a href="{{ whatsapp_url() }}" target="_blank" rel="noopener" class="kral-contact-card__sub">WhatsApp İletişim Hattı</a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="kral-contact-card">
                        <i class="bi bi-telephone" aria-hidden="true"></i>
                        <h2>Telefon</h2>
                        <a href="{{ phone_href(setting('phone_landline')) }}">{{ setting('phone_landline') }}</a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="kral-contact-card">
                        <i class="bi bi-envelope" aria-hidden="true"></i>
                        <h2>E-posta</h2>
                        <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="kral-contact-card">
                        <i class="bi bi-geo-alt" aria-hidden="true"></i>
                        <h2>Adres</h2>
                        <address class="m-0">{{ setting('address') }}</address>
                        <a href="{{ setting('map_link') }}" target="_blank" rel="noopener" class="kral-contact-card__sub">Yol tarifi al</a>
                    </div>
                </div>
            </div>

            <div class="row g-4 g-lg-5">
                <div class="col-lg-7" id="iletisim-formu">
                    <div class="kral-form-box">
                        <h2 class="kral-form-box__title">Bize Yazın</h2>
                        <p class="kral-form-box__intro">Formu doldurun, size en kısa sürede dönüş yapalım. <span class="text-nowrap">* işaretli alanlar zorunludur.</span></p>

                        @if (session('contact_success'))
                            <div class="alert alert-success kral-alert" role="status" tabindex="-1" data-autofocus>
                                <i class="bi bi-check-circle" aria-hidden="true"></i> {{ session('contact_success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger kral-alert" role="alert" tabindex="-1" data-autofocus>
                                <strong>Form gönderilemedi.</strong> Lütfen işaretli alanları kontrol edin.
                                @if ($errors->has('form_token') || $errors->has('website'))
                                    <div>{{ $errors->first('form_token') ?: $errors->first('website') }}</div>
                                @endif
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.store') }}" class="kral-form" novalidate>
                            @csrf
                            <input type="hidden" name="form_token" value="{{ $formToken }}">
                            {{-- Bal küpü: ekran okuyuculardan ve kullanıcılardan gizli --}}
                            <div class="kral-hp" aria-hidden="true">
                                <label for="website">Web siteniz</label>
                                <input type="text" name="website" id="website" value="" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Ad Soyad *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required maxlength="100" autocomplete="name" @error('name') aria-describedby="name-error" @enderror>
                                    @error('name')<div class="invalid-feedback" id="name-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Telefon *</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" required maxlength="20" autocomplete="tel" inputmode="tel" placeholder="05xx xxx xx xx" @error('phone') aria-describedby="phone-error" @enderror>
                                    @error('phone')<div class="invalid-feedback" id="phone-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-posta</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" maxlength="150" autocomplete="email" @error('email') aria-describedby="email-error" @enderror>
                                    @error('email')<div class="invalid-feedback" id="email-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="service_id" class="form-label">Hizmet</label>
                                    @php($selected = old('service_id', $services->firstWhere('slug', $selectedService)?->id))
                                    <select id="service_id" name="service_id" class="form-select @error('service_id') is-invalid @enderror">
                                        <option value="">Hizmet seçin (isteğe bağlı)</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}" @selected((string) $selected === (string) $service->id)>{{ $service->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('service_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Mesajınız *</label>
                                    <textarea id="message" name="message" rows="5" class="form-control @error('message') is-invalid @enderror" required maxlength="3000" placeholder="Araç marka/model ve hasar hakkında kısa bilgi" @error('message') aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
                                    @error('message')<div class="invalid-feedback" id="message-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input @error('kvkk') is-invalid @enderror" type="checkbox" value="1" id="kvkk" name="kvkk" @checked(old('kvkk')) required>
                                        <label class="form-check-label" for="kvkk">
                                            Paylaştığım kişisel verilerin, iletişim talebime yanıt verilmesi amacıyla işlenmesini kabul ediyorum. *
                                        </label>
                                        @error('kvkk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-two kral-submit">
                                        <span class="btn-wrap">
                                            <span class="text-first">Gönder</span>
                                            <span class="text-second" aria-hidden="true"><i class="bi bi-send"></i> <i class="bi bi-send"></i></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="kral-map">
                        <iframe src="{{ setting('map_embed_url') }}" title="Kral Oto Cam konumu - Google Haritalar" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    </div>
                    <div class="kral-map-note">
                        <p class="mb-2"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ setting('address') }}</p>
                        @if (setting('working_hours'))
                            <p class="mb-2"><i class="bi bi-clock" aria-hidden="true"></i> {{ setting('working_hours') }}</p>
                        @endif
                        <a href="{{ setting('map_link') }}" target="_blank" rel="noopener">Google Haritalar'da aç <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
