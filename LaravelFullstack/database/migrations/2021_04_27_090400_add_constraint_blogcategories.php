<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConstraintBlogcategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogcategories', function (Blueprint $table) {
           $table->unsignedBigInteger('blog_id')->change();
            $table->foreign('blog_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');

            $table->unsignedBigInteger('category_id')->change();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
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
        Schema::table('blogcategories', function (Blueprint $table) {
            //
        });
    }
}

/*
Note
 */
//(1) add constraint to  existing column of a trable
//a)add "->change()" to exsting column
//b)run this cmd: composer require doctrine/dbal (version ^2 only, version 3 will cause error)
//ref: https://stackoverflow.com/a/46280946/11297747