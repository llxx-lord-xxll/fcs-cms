<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SitePackageGroup extends Model
{
    protected $table = 'site_package_group';

    public static function getAllPackageGroups(){
        $ret = array();

        foreach (DB::table('site_package_group')->get() as $item)
        {
            $ret[$item->id] = $item->title;
        }

        return $ret;
    }
    public function packages()
    {
        $this->hasMany(SitePackages::class,'package_group_id');
    }
}
