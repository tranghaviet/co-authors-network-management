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
            $table->string('id', 24);
            // $table->string('first_author_id', 12);
            $table->bigInteger('first_author_id', false, true)->index();
            // $table->string('second_author_id', 12);
            $table->bigInteger('second_author_id', false, true)->index();
            $table->smallInteger('no_of_mutual_authors', false, true)->nullable();
            $table->smallInteger('no_of_joint_papers', false, true)->nullable();
            $table->smallInteger('no_of_joint_subjects', false, true)->nullable();
            $table->smallInteger('no_of_joint_keywords', false, true)->nullable();

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
