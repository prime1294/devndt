<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Donation extends Model
{
  use SoftDeletes;
  protected $table = 'donation';

  public static function insertIgnore($array){
    $a = new static();
    if($a->timestamps){
        $now = \Carbon\Carbon::now();
        $array['created_at'] = $now;
        $array['updated_at'] = $now;
    }
    DB::insert('INSERT IGNORE INTO '.$a->table.' ('.implode(',',array_keys($array)).
        ') values (?'.str_repeat(',?',count($array) - 1).')',array_values($array));
  }
}
