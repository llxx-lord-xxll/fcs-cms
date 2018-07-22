<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteTeamPeople extends Model
{
    protected $table = 'site_teams_people';

    public $timestamps = false;

    public static function allNodes()
    {
        $ret = array();

        foreach (SiteTeamPeople::get() as $item)
        {
            $ret[$item->id] = $item->name;
        }

        return $ret;
    }

}
