<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAuthorTableToAuthors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('author', function (Blueprint $t) {
//           $t->renameColumn('givenName', 'given_name');
//           $t->addColumn('integer', 'university_id', ['unsigned' => true])->nullable();
////           $t->dropColumn(['affiliation', 'subjects']);
//           $t->rename('authors');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('authors', function (Blueprint $t) {
//           $t->dropColumn('university_id');
//           $t->renameColumn('given_name', 'givenName');
//           $t->rename('author');
//        });
    }
}
