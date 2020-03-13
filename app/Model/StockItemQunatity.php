<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockItemQunatity extends Model
{
  use SoftDeletes;
  protected $table = 'stock_item_quantity';
}
