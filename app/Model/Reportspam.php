<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reportspam extends Model
{
    protected $table = 'spam';
    protected $fillable = ['spam_business','spam_uid'];
}
