<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
  use SoftDeletes;
  protected $table = 'payment';

  public function scopeHaveRightBank($query,$uid,$bid)
  {
      return $query->where('user_id', $uid)
      ->where('id',$bid)->get()->isNotEmpty();
  }
}
