<?php

namespace App\Filament\Resources\FleetPartners\Pages;

use App\Filament\Resources\FleetPartners\FleetPartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFleetPartner extends EditRecord
{
    protected static string $resource = FleetPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
