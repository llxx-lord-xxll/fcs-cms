<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteSlider extends Model
{
    protected $table = 'site_slider';

    public $timestamps = false;

    public static function allNodes()
    {
        $ret = array();

        foreach (SiteSlider::get() as $item)
        {
            $ret[$item->id] = $item->title;
        }

        return $ret;
    }
}
