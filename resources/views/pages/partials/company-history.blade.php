{{--
    Kurumsal geçmiş (Hakkımızda). Kuruluş yılı yalnızca panelde "doğrulandı" işaretlenince gösterilir
    (founding_year() doğrulanmamışsa null döner). Deneyim yılı salt yıl farkıdır.
--}}
@php($year = founding_year())
@if ($year || setting('company_history') || setting('experience_text'))
    <div class="kral-history mt-5" data-company-history>
        <h2>Kurumsal Geçmişimiz</h2>
        @if ($year)
            <p class="kral-history__year" data-founding-year>
                <strong>{{ $year }}</strong> yılında kuruldu
                @if (experience_years() > 0)
                    <span>· {{ experience_years() }} yıl</span>
                @endif
            </p>
        @endif
        @if (setting('experience_text'))
            <p>{{ setting('experience_text') }}</p>
        @endif
        @if (setting('company_history'))
            {!! nl2br(e(setting('company_history'))) !!}
        @endif
    </div>
@endif
