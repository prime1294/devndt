<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BusinessWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::defaultStringLength(191);
      Schema::create('working_hours', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('profile_id')->unsigned();
          $table->time('start_time');
          $table->time('end_time');
          $table->tinyInteger('is_off');
          $table->tinyInteger('day_id')->unsigned();
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
        Schema::dropIfExists('working_hours');
    }
}
