<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BusinessReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::defaultStringLength(191);
      Schema::create('business_report', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('profile_id')->unsigned();
          $table->integer('user_id')->unsigned();
          $table->string('title');
          $table->text('description');
          $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
          $table->softDeletes();
          $table->unique('user_id','profile_id');
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
        Schema::dropIfExists('business_report');
    }
}
