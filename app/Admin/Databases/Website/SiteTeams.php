<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteTeams extends Model
{
    protected $table = 'site_teams';

    public $timestamps = false;

    public static function allNodes()
    {
        $ret = array();

        foreach (SiteTeams::get() as $item)
        {
            $ret[$item->id] = $item->title;
        }

        return $ret;
    }
}
