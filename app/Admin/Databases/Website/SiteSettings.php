<?php

namespace App\Admin\Databases\Website;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $table = 'site_general_settings';

    public $timestamps = false;

    protected $fillable = ['meta_value'];
}
