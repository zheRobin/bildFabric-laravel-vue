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
            $table->string('uploaded_file_path')->nullable();
            $table->string('original_file_name')->nullable();
            $table->dropColumn('data');
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('headers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_items', function (Blueprint $table) {
            $table->dropColumn('uploaded_file_path');
            $table->dropColumn('original_file_name');
            $table->longText('headers')->nullable();
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->longText('headers')->nullable();;
        });
    }
};
