<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrameReport extends Model
{
  use SoftDeletes;
  protected $table = 'frame_report';

  public function scopeHaveRightBank($query,$uid,$bid)
  {
      return $query->where('user_id', $uid)
      ->where('id',$bid)->get()->isNotEmpty();
  }
}
