<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workinghours extends Model
{
  use SoftDeletes;
  protected $table = 'working_hours';
  protected $fillable = ['day_id','profile_id','start_time','end_time','is_off'];
}
