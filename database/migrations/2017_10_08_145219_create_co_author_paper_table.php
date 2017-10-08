<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoAuthorPaperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_author_paper', function (Blueprint $table) {
            $table->integer('co_author_id', false, true);
            $table->integer('paper_id', false, true);

            $table->foreign('co_author_id')
                ->references('id')
                ->on('co_authors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('paper_id')
                ->references('id')
                ->on('papers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_author_paper');
    }
}
