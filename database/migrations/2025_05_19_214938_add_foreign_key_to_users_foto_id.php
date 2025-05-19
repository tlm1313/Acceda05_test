<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // En el archivo de migración generado
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // 1. Asegurar que la columna sea del tipo correcto
        $table->unsignedBigInteger('foto_id')->nullable()->change();

        // 2. Añadir la clave foránea
        $table->foreign('foto_id')
              ->references('id')
              ->on('fotos')
              ->onDelete('set null'); // Opcional: cascade o restrict
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['foto_id']);
    });
}
};
