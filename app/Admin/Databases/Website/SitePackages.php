<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SitePackages extends Model
{
    protected $table = 'site_packages';
    protected $fillable = ['title'];
    public $timestamps = false;

    public function package_meta()
    {
        return $this->hasMany(SitePackagesMeta::class, 'package_id');
    }
    public function groups()
    {
        $this->belongsTo(SitePackageGroup::class,'package_group_id');
    }


}
