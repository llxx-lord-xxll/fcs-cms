<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteBlogCategory extends Model
{

    public static function allCats()
    {
        $tmp = array();
        foreach (SiteBlogCategory::get() as $category)
        {
            $tmp[$category->id] = $category->category_name;
        }
        return $tmp;
    }
}
