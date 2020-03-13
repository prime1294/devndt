<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialType extends Model
{
  use SoftDeletes;
  protected $table = 'material_type';
}
