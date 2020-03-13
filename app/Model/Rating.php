<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'rating';
    protected $fillable = ['profile_id','user_id','rating'];
}
