<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_subject', function (Blueprint $table) {
            // $table->string('author_id', 12);
            $table->bigInteger('author_id', false, true);
            $table->integer('subject_id', false, true);

            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->primary(['author_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('author_subject');
    }
}
