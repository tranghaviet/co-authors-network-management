<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordPaperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_paper', function (Blueprint $table) {
            $table->integer('keyword_id', false, true);
            $table->integer('paper_id', false, true);

            $table->foreign('keyword_id')
                ->references('id')
                ->on('keywords')
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
        Schema::dropIfExists('keyword_paper');
    }
}
