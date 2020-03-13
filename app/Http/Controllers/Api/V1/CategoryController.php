<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Category;
use Api;

class CategoryController extends Controller
{
    public function getAllCategory()
    {
      $parent = [];
      $category = Category::select("id","name")->where('parent id',0)->where('status',1)->orderBy('name','ASC')->get()->toArray();
      if(!empty($category)) {
        foreach($category as $row) {
          $subcategory = Category::select("id","name")->where('parent id',$row['id'])->where('status',1)->orderBy('name','ASC')->get()->toArray();
          $row['subcategory'] = $subcategory;
          array_push($parent,$row);
        }
        return Api::apiresponse("true","success",$parent);
      } else {
        return Api::apiresponse("false","No category avalible");
      }
    }
}
