<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigInteger('id', false, true);
            // $table->string('id', 12);
            $table->string('given_name', 45);
            $table->string('surname', 45);
            $email = $table->string('email')->nullable();
            $url = $table->string('url')->nullable();
            $table->integer('university_id', false, true)->nullable();

            $email->collation = $url->collation = 'ascii_general_ci';

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
