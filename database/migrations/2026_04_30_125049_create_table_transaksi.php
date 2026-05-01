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
        Schema::create('tbl_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained("tbl_outlet")->cascadeOnDelete();
            $table->string("kode_invoice", 100);
            $table->foreignId('member_id')->constrained("tbl_member")->cascadeOnDelete();
            $table->dateTime("tgl");
            $table->dateTime("batas_waktu");
            $table->dateTime("tgl_bayar")->nullable();
            $table->integer("biaya_tambahan")->nullable();
            $table->double("diskon")->nullable();
            $table->integer("pajak")->nullable();
            $table->enum("status", ['baru', 'proses', 'selesai', 'diambil']);
            $table->enum("dibayar", ['dibayar', 'belum_dibayar']);
            $table->foreignId('user_id')->constrained("tbl_user")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_transaksi');
    }
};
