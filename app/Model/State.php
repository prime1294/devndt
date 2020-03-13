<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;
    protected $table = 'states';
     protected $fillable = ['country_id','state','gst_code'];
}
