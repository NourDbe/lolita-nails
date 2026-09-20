<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('reels', function (Blueprint $table) { $table->id(); $table->string('title')->nullable(); $table->string('video'); $table->string('poster')->nullable(); $table->string('label')->default('Before → After'); $table->boolean('is_published')->default(true); $table->unsignedInteger('sort_order')->default(0); $table->timestamps(); }); } public function down(): void { Schema::dropIfExists('reels'); } };
