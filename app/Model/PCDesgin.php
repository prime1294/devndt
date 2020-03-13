<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PCDesgin extends Model
{
  use SoftDeletes;
  protected $table = 'programme_card_design';
}
