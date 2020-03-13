<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BusinessProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::defaultStringLength(191);
      Schema::create('business_profile', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('user_id')->unsigned();
          $table->string('name',200);
          $table->text('logo');
          $table->string('email');
          $table->string('mobile',100);
          $table->text('website');
          $table->integer('country')->unsigned();
          $table->integer('state')->unsigned();
          $table->integer('city')->unsigned();
          $table->text('address');
          $table->string('latitude');
          $table->string('longitude');
          $table->integer('category_level_1')->unsigned();
          $table->integer('category_level_2')->unsigned();
          $table->tinyInteger('status')->default(1);

          $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
          $table->softDeletes();
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
        Schema::dropIfExists('business_profile');
    }
}
