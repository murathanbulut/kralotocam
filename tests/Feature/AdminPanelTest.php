<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Services\Pages\EditService;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SiteContentSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed([ServiceSeeder::class, SiteContentSeeder::class]);
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk();
    }

    public function test_non_admin_user_cannot_access_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_open_every_resource_page(): void
    {
        $this->actingAs($this->admin());

        $this->get('/admin')->assertOk();

        foreach (Filament::getPanel('admin')->getResources() as $resource) {
            $this->get($resource::getUrl('index'))->assertOk();

            if ($resource::hasPage('create')) {
                $this->get($resource::getUrl('create'))->assertOk();
            }

            $record = $resource::getModel()::query()->first();

            if ($record && $resource::hasPage('edit')) {
                $this->get($resource::getUrl('edit', ['record' => $record]))->assertOk();
            }

            if ($record && $resource::hasPage('view')) {
                $this->get($resource::getUrl('view', ['record' => $record]))->assertOk();
            }
        }
    }

    public function test_admin_can_open_settings_page(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/site-ayarlari')
            ->assertOk()
            ->assertSee('Site Ayarları');
    }

    public function test_admin_can_update_site_settings(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManageSettings::class)
            ->fillForm(['working_hours' => 'Hafta içi 08:30 - 18:30', 'instagram_url' => 'https://www.instagram.com/ornek'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Hafta içi 08:30 - 18:30', Setting::get('working_hours'));
        $this->get('/iletisim/')->assertSee('Hafta içi 08:30 - 18:30')->assertSee('https://www.instagram.com/ornek', false);
    }

    public function test_admin_can_edit_service_and_change_is_visible_on_site(): void
    {
        $this->actingAs($this->admin());
        $service = Service::query()->where('slug', 'cam-filmi')->firstOrFail();

        Livewire::test(EditService::class, ['record' => $service->getKey()])
            ->fillForm(['excerpt' => 'Güncellenmiş kısa açıklama.'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get('/cam-filmi/')->assertSee('Güncellenmiş kısa açıklama.');
    }

    public function test_page_slug_cannot_collide_with_service_or_reserved_paths(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreatePage::class)
            ->fillForm(['title' => 'Deneme', 'slug' => 'cam-filmi'])
            ->call('create')
            ->assertHasFormErrors(['slug']);

        Livewire::test(CreatePage::class)
            ->fillForm(['title' => 'Deneme', 'slug' => 'galeri'])
            ->call('create')
            ->assertHasFormErrors(['slug']);

        Livewire::test(CreatePage::class)
            ->fillForm(['title' => 'Deneme', 'slug' => 'Türkçe Adres'])
            ->call('create')
            ->assertHasFormErrors(['slug']);
    }
}
