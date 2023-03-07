<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('content');
            $table->foreignId('category_id')
                ->constrained()
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->boolean('exists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
