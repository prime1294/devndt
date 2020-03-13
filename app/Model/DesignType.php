<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesignType extends Model
{
  use SoftDeletes;
  protected $table = 'design_type';
}
