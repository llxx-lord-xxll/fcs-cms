<?php

namespace App\Admin\Databases\Website;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $table = 'site_general_settings';

    public $timestamps = false;

    protected $fillable = ['meta_value'];

    public static function getArray(){
        $tmp = array();

        foreach (SiteSettings::get() as $item)
        {
            $tmp[$item->meta_key] = $item->meta_value;
        }
        return $tmp;
    }
}
