<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Support\Seo;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $seo = Seo::make(
            'Oto Cam Hizmetlerimiz | Eskişehir',
            'Kral Oto Cam hizmetleri: binek ve ticari araç cam değişimi, iş makinesi camları, oto cam onarımı, ADAS kalibrasyonu, cam filmi ve ayna onarımı.'
        )->breadcrumbs([['Hizmetler']]);

        return view('services.index', [
            'seo' => $seo,
            'services' => Service::query()->active()->ordered()->get(),
        ]);
    }
}
