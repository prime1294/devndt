<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ref extends Model
{
    use SoftDeletes;
    protected $table = 'ref';
}
