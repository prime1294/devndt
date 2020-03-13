<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessReceive extends Model
{
    use SoftDeletes;
    protected $table = 'process_receive';
}
