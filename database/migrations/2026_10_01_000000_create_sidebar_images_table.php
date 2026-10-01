<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sidebar_images', function (Blueprint $table) {
            $table->id();
            // طول این ستون‌ها عمداً کوتاه نگه داشته شده تا کلید یکتا (key + disk)
            // زیر حداکثر طول ایندکس MySQL (۱۰۰۰ بایت) باقی بماند.
            $table->string('key', 64)->default('default')->index();
            $table->string('title')->nullable();
            $table->string('path');
            $table->string('disk', 32)->default('public');
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
