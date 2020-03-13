<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    protected $table = 'countries';
    //protected $hidden = ['created_at','updated_at','deleted_at'];
}
