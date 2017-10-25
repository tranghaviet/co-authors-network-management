<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            // $table->increments('id');
            $table->string('id', 15);
            $table->string('given_name', 45);
            $table->string('surname', 45);
            $table->string('email')->nullable();
            $table->string('url')->nullable();
            // $table->string('university')->nullable();
            $table->integer('university_id', false, true);

            $table->primary('id');

            $table->foreign('university_id')
                ->references('id')
                ->on('universities')
                ->onUpdate('cascade')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors');
    }
}
