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
        Schema::table('collection_items', function (Blueprint $table) {
            $table->string('source')->nullable();
            $table->string('title')->nullable();
            $table->dropColumn('data');
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('headers');
            $table->dropColumn('last_uploaded_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_items', function (Blueprint $table) {
            $table->dropColumn('source');
            $table->dropColumn('title');
            $table->longText('data')->nullable();
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->longText('headers')->nullable();
            $table->longText('last_uploaded_file_path')->nullable();
        });
    }
};
