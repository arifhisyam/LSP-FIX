<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->unsignedBigInteger('id_kategori');
            $table->string('kode_produk', 50)->unique();
            $table->string('nama_produk', 150);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_beli', 15,2)->default(0);
            $table->decimal('harga_jual', 15,2)->default(0);
            $table->integer('stok')->default(0);
            $table->string('satuan', 30);
            $table->text('keterangan')->nullable();
            

            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
