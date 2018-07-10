<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteMenus extends Model
{
    protected $table = 'site_menus';

    public $timestamps = false;

    public static function allMenus()
    {
        $tmp = array();
        foreach (SiteMenus::get() as $menu)
        {
            $tmp[$menu->id] = $menu->title;
        }
        return $tmp;
    }

}
