<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Anlaşmalı sigorta şirketleri ve filo kuruluşları.
 * Kayıtlar yalnızca "doğrulandı" + "aktif" olduğunda ön yüzde listelenir;
 * logolar ayrıca kullanım hakkı onaylanırsa gösterilir.
 * pages.template: özel liste içeren kurumsal sayfaları (sigorta/filo) adresten bağımsız tanımlar.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['insurance_companies', 'fleet_partners'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('logo')->nullable();
                $table->boolean('logo_rights_confirmed')->default(false);
                $table->text('description')->nullable();
                $table->boolean('is_verified')->default(false);
                $table->timestamp('verified_at')->nullable();
                $table->text('internal_note')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique('name');
                $table->index(['is_active', 'is_verified', 'sort_order']);
            });
        }

        Schema::table('pages', function (Blueprint $table) {
            $table->string('template', 30)->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('template');
        });

        Schema::dropIfExists('fleet_partners');
        Schema::dropIfExists('insurance_companies');
    }
};
