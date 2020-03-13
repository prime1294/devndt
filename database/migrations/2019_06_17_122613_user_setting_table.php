<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::defaultStringLength(191);
      Schema::create('user_setting', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('user_id')->unsigned();
          $table->integer('push_notification')->unsigned();
          $table->integer('email_notification')->unsigned();
          $table->integer('profile_visiblity')->unsigned();
          $table->integer('deactive_mail')->unsigned();
          $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
          $table->softDeletes();
          $table->unique('user_id');
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
        Schema::dropIfExists('user_setting');
    }
}
