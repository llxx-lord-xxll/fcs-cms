<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteSchedule extends Model
{
    protected $table = 'site_schedules';

    protected $fillable = array('title');
    public function events()
    {
        return $this->hasMany(SiteScheduleMeta::class, 'schedule_id');
    }

    public static function allNodes()
    {
        $ret = array();

        foreach (SiteSchedule::get() as $item)
        {
            $ret[$item->id] = $item->title;
        }

        return $ret;
    }
}
