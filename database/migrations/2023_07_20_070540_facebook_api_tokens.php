<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facebook_api_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('user_page_id');
            $table->string('user_page_name');
            $table->text('access_token');
            $table->boolean('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_api_tokens');
    }
};
