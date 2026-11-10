<?php

namespace App\Filament\Resources\InsuranceCompanies;

use App\Filament\Resources\InsuranceCompanies\Pages\CreateInsuranceCompany;
use App\Filament\Resources\InsuranceCompanies\Pages\EditInsuranceCompany;
use App\Filament\Resources\InsuranceCompanies\Pages\ListInsuranceCompanies;
use App\Filament\Support\OrganizationSchema;
use App\Models\InsuranceCompany;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InsuranceCompanyResource extends Resource
{
    protected static ?string $model = InsuranceCompany::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Kurumsal';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'sigorta şirketi';

    protected static ?string $pluralModelLabel = 'Sigorta Şirketleri';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return OrganizationSchema::form($schema, 'Şirket adı', 'Anlaşma doğrulandı', 'uploads/sigorta');
    }

    public static function table(Table $table): Table
    {
        return OrganizationSchema::table($table, 'Şirket adı', 'Anlaşma doğrulandı');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInsuranceCompanies::route('/'),
            'create' => CreateInsuranceCompany::route('/create'),
            'edit' => EditInsuranceCompany::route('/{record}/edit'),
        ];
    }
}
