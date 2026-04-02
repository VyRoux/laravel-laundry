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
        Schema::create('table_member', function (Blueprint $table) {
            $table->id();
            $table->string("nama", 100);
            $table->text("alamat")->nullable();
            $table->enum("jenis_kelamin", ["laki-laki","perempuan"]);
            $table->string("telepon", 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_table_member');
    }
};
