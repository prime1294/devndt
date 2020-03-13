<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
  // use SoftDeletes;
  protected $table = 'user_activity';
  protected $hidden = array('created_at','updated_at');
}
