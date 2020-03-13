<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockUnit extends Model
{
  use SoftDeletes;
  protected $table = 'stock_unit';
}
