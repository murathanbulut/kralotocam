<?php

namespace App\Filament\Resources\FleetPartners\Pages;

use App\Filament\Resources\FleetPartners\FleetPartnerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFleetPartners extends ListRecords
{
    protected static string $resource = FleetPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
