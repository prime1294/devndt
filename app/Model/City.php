<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;
    protected $fillable = ['country_id','state_id','city'];
    protected $table = 'cities';
}
