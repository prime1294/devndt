<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cource extends Model
{
    use SoftDeletes;
    protected $table = 'cource';
    protected $hidden = ['create_at','updated_at','deleted_at'];
}
