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
            // $table->increments('id');
            $id = $table->string('id', 23);
            $table->mediumText('title');
            $table->datetime('cover_date')->nullable();
            $table->mediumText('abstract');
            $url = $table->string('url')->nullable();
            $issn = $table->string('issn', 50)->nullable();

            $id->collation = $url->collation = $issn->collation = 'ascii_general_ci';

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
