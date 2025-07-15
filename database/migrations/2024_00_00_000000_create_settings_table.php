<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->string('tenant_id')->nullable()->index();
        });
    }

    public function down(): void {
        Schema::dropIfExists('settings');
    }
};
