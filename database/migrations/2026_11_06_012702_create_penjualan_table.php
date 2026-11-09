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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->string('no_nota', 50)->unique();
            $table->date('tgl_penjualan');
            $table->decimal('total', 15,2)->default(0);
            $table->decimal('diskon', 15,2)->default(0);
            $table->decimal('pembayaran', 15,2)->default(0);
            $table->decimal('kembalian', 15,2)->default(0);
            $table->string('stok_akhir');
            $table->text('keterangan')->nullable();
            

            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('user')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreign('id_pelanggan')
                  ->references('id_pelanggan')
                  ->on('pelanggan')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
