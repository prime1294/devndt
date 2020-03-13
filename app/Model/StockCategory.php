<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockCategory extends Model
{
  use SoftDeletes;
  protected $table = 'stock_category';
}
