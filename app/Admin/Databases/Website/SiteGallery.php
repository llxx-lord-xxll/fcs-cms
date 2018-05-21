<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteGallery extends Model
{
    protected $table = 'site_gallery';

    public $timestamps = false;


    public static function allNodes()
    {
        $ret = array();

        foreach (SiteGallery::get() as $item)
        {
            $ret[$item->id] = $item->title;
        }

        return $ret;
    }

}
