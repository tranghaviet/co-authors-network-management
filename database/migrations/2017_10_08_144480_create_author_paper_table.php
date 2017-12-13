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
            //$table->charset = 'ascii';
            //$table->collation = 'ascii_general_ci';

            $table->bigInteger('author_id', false, true);
            $table->string('paper_id', 23);

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

            $table->primary(['author_id', 'paper_id']);
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
