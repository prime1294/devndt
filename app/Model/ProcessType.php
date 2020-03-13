<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessType extends Model
{
  use SoftDeletes;
  protected $table = 'process_type';
}
