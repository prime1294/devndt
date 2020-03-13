<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductAttribTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::defaultStringLength(191);
      Schema::create('product_attrib', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('product_id')->unsigned();
          $table->string('title');
          $table->text('description');
          $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
          $table->softDeletes();
          $table->unique('title');
          $table->engine = 'InnoDB';
          // $table->charset('utf8');
          // $table->collation('utf8_unicode_ci');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attrib');
    }
}
