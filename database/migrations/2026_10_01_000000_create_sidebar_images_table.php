<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sidebar_images', function (Blueprint $table) {
            $table->id();
            $table->string('key')->default('default')->index();
            $table->string('title')->nullable();
            $table->string('path');
            $table->string('disk')->default('public');
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();

            $table->unique(['key', 'disk']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sidebar_images');
    }
};
