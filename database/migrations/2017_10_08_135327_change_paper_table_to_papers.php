<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePaperTableToPapers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('paper', function (Blueprint $t) {
//            $t->renameColumn('coverDate', 'cover_date');
////            $t->dropColumn('keywords');
//            $t->rename('papers');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('papers', function (Blueprint $t) {
//            $t->renameColumn('cover_date', 'coverDate');
//        });
    }
}
