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
        Schema::create('tbl_paket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained("tbl_outlet")->cascadeOnDelete();
            $table->enum("jenis", ['kiloan', 'selimut', 'bed_cover', 'kaos', 'lainnya']);
            $table->string("nama_paket", 100);
            $table->integer("harga");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_paket');
    }
};
