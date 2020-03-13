<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
  public $table = 'setting';
  protected $hidden = array('created_at','updated_at','deleted_at');
}
