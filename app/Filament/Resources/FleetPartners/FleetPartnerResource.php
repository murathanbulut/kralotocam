<?php

namespace App\Filament\Resources\FleetPartners;

use App\Filament\Resources\FleetPartners\Pages\CreateFleetPartner;
use App\Filament\Resources\FleetPartners\Pages\EditFleetPartner;
use App\Filament\Resources\FleetPartners\Pages\ListFleetPartners;
use App\Filament\Support\OrganizationSchema;
use App\Models\FleetPartner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FleetPartnerResource extends Resource
{
    protected static ?string $model = FleetPartner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|UnitEnum|null $navigationGroup = 'Kurumsal';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'filo kuruluşu';

    protected static ?string $pluralModelLabel = 'Filo Kuruluşları';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return OrganizationSchema::form($schema, 'Kuruluş adı', 'İş birliği doğrulandı', 'uploads/filo');
    }

    public static function table(Table $table): Table
    {
        return OrganizationSchema::table($table, 'Kuruluş adı', 'İş birliği doğrulandı');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFleetPartners::route('/'),
            'create' => CreateFleetPartner::route('/create'),
            'edit' => EditFleetPartner::route('/{record}/edit'),
        ];
    }
}
