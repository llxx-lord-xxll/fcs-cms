<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteScheduleMeta extends Model
{
    protected $table = 'site_schedules_meta';
    protected $fillable = array('title','subtitle','speakers','moderator','time_period_start','time_period_end','location','host');
    public $timestamps = false;

    public function schedules()
    {
        return $this->belongsTo(SiteSchedule::class, 'schedule_id');
    }

}
