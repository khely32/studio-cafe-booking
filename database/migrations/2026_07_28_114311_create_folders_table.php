<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#8B6F47');
            $table->timestamps();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->unsignedBigInteger('folder_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('folder_id');
        });
        Schema::dropIfExists('folders');
    }
};
