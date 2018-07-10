<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteFooter extends Model
{
    protected $table = 'site_footer';
    protected $fillable = array('meta_key','meta_value');

    public $timestamps = false;
    public static function getArray(){
        $tmp = array();

        foreach (SiteFooter::get() as $item)
        {
            $tmp[$item->meta_key] = $item->meta_value;
        }
        return $tmp;
    }

}
