<?php

// database/migrations/xxxx_xx_xx_create_site_settings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable();

            $table->string('hero_title');
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_button_text')->nullable();
            $table->string('hero_button_link')->nullable();
            $table->string('hero_image_path')->nullable();

            $table->string('about_title')->nullable();
            $table->text('about_subtitle')->nullable();

            $table->string('facebook_url')->nullable();
            $table->string('viber_url')->nullable();

            $table->string('footer_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
