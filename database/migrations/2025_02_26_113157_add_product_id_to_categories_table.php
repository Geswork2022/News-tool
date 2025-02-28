<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->after('id');
            // Si tu veux une clé étrangère, ajoute :
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Pour annuler la clé étrangère si ajoutée
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
    }
};
