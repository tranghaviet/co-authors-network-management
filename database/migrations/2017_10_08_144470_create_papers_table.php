<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papers', function (Blueprint $table) {
            // TODO: Change charset = utf8, collation = utf8_general_ci in table manually
            $table->charset = 'ascii';
            $table->collation = 'ascii_general_ci';
            // $table->increments('id');
            $table->string('id', 23);
            $table->mediumText('title');
            $table->datetime('cover_date')->nullable();
            $table->mediumText('abstract');
            $table->string('url')->nullable();
            $table->string('issn', 50)->nullable();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('papers');
    }
}
