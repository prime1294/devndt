<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;
    protected $table = 'business_plan';
    protected $hidden = array('created_at','updated_at','deleted_at');
}
