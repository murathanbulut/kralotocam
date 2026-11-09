Web sitesi iletişim formundan yeni bir mesaj alındı.

Ad Soyad : {{ $contactMessage->name }}
Telefon  : {{ $contactMessage->phone }}
E-posta  : {{ $contactMessage->email ?: '-' }}
Hizmet   : {{ $contactMessage->service_title ?: '-' }}
Tarih    : {{ $contactMessage->created_at->format('d.m.Y H:i') }}

Mesaj:
{{ $contactMessage->message }}

--
Bu mesaj yönetim panelinde "Gelen Mesajlar" bölümünde de kayıtlıdır.
