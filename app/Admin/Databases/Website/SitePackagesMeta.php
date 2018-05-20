<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SitePackagesMeta extends Model
{
    protected $table = 'site_packages_meta';
    protected $fillable = ['title','description'];
    public $timestamps = false;

    public function packages()
    {
        $this->belongsTo(SitePackages::class,'package_id');
    }
}
