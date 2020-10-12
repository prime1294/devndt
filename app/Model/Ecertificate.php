<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ecertificate extends Model
{
    use SoftDeletes;
    protected $table = 'enrollment_certificate';
}
