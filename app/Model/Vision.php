<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vision extends Model
{
    use SoftDeletes;
    protected $table = 'vision';
}
