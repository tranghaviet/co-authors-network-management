<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorPaperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_paper', function (Blueprint $table) {
            $table->integer('author_id', false, true);
            $table->integer('paper_id', false, true);
            $table->integer('university_id', false, true);

            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('paper_id')
                ->references('id')
                ->on('papers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('author_paper');
    }
}
