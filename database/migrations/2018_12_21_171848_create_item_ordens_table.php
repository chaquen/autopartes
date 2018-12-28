<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemOrdensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_ordens', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('orden_id');
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('estadoItem_id');
            $table->unsignedInteger('sede_id');
            $table->string('marca');
            $table->string('referencia');
            $table->string('descripcion')->nullable();
            $table->string('cantidad');
            $table->string('comentarios')->nullable();

            $table->decimal('pesoLb')->nullable();
            $table->decimal('costoUnitario')->nullable();
            $table->decimal('margenUsa')->nullable();

            $table->string('diasEntregaProv')->nullable();
            $table->string('bodega')->nullable();
            $table->string('guiaInternacional')->nullable();
            $table->string('invoice')->nullable();
            $table->string('fechaInvoice')->nullable();
            $table->string('diasPrometidos')->nullable();
            $table->string('guiaInterna')->nullable();
            $table->string('facturaCop')->nullable();
            $table->string('fechaRealEntrega')->nullable();
            $table->string('fechaFactura')->nullable();
            $table->string('porcentajeArancel')->nullable();
            $table->string('margenCop')->nullable();
            $table->string('TE')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_ordens');
    }
}
