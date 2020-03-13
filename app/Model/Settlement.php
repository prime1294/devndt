<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settlement extends Model
{
  use SoftDeletes;
  protected $table = 'settlement';

  public static function scopeHaveRightBank($query,$uid,$bid)
  {
      return $query->where('user_id', $uid)
      ->where('id',$bid)->get()->isNotEmpty();
  }
}
