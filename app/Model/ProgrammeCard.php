<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgrammeCard extends Model
{
    use SoftDeletes;
    protected $table = 'programme_card';
    protected $fillable = ['pc_unique_number','user_id'];

    public static function scopeHaveRightBank($query,$uid,$bid)
    {
        return $query->where('user_id', $uid)
        ->where('id',$bid)->get()->isNotEmpty();
    }
}
