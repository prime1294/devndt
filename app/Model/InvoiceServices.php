<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceServices extends Model
{
  use SoftDeletes;
  protected $table = 'invoice_services';
}
