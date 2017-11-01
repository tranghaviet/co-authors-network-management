<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_authors', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('first_author_id', 12);
            $table->bigInteger('first_author_id', false, true);
            // $table->string('second_author_id', 12);
            $table->bigInteger('second_author_id', false, true);

            $table->index(['first_author_id', 'second_author_id']);
            $table->unique(['first_author_id', 'second_author_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_authors');
    }
}
