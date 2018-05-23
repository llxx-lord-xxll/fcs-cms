<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteTeamsMeta extends Model
{
    protected $table = 'site_teams_meta';

    public $timestamps = false;

    public static function getTeam($model)
    {
        $ret = array();
        if ($model != null)
        {
            $t = 0;
            foreach (SiteTeamsMeta::where('people_id','=',$model)->get() as $item)
            {
                $ret[$t] = $item->teams_id;
                $t++;
            }
        }
        return $ret;
    }

    public static function getPeople($model)
    {
        $ret = array();
        if ($model != null)
        {
            $t = 0;
            foreach (SiteTeamsMeta::where('teams_id','=',$model)->get() as $item)
            {
                $ret[$t] = $item->people_id;
                $t++;
            }
        }
        return $ret;
    }

}
