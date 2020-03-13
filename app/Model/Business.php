<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use JustBetter\PaginationWithHavings\PaginationWithHavings;

class Business extends Model
{
    use SoftDeletes;
    // use PaginationWithHavings;
    protected $table = 'business_profile';

    protected $fillable = ['user_id'];

    public function getQualifiedDeletedAtColumn()
    {
        return 'b.deleted_at';
    }

}
