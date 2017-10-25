<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('co_author_id', false, true);
            $table->smallInteger('no_of_mutual_authors', false, true)->nullable();
            $table->smallInteger('no_of_joint_papers', false, true)->nullable();
            $table->smallInteger('no_of_joint_subjects', false, true)->nullable();
            $table->smallInteger('no_of_joint_keywords', false, true)->nullable();
            $table->float('score_1')->nullable();
            $table->float('score_2')->nullable();
            $table->float('score_3')->nullable();

            $table->foreign('co_author_id')
                ->references('id')
                ->on('co_authors')
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
        Schema::dropIfExists('candidates');
    }
}
