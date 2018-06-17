<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteFooter extends Model
{
    protected $table = 'site_footer';

    public $timestamps = false;

    public static function getTabs(){
        $tmp = array();

        foreach (SiteFooter::where('parent','=','0')->where('meta_type','=','tab')->get() as $item)
        {
            $tmp[$item->id] = $item->title;
        }

        return $tmp;
    }

    public function tab()
    {
        $this->belongsTo(SiteFooter::class,'parent');
    }
    public function listItems()
    {
        return $this->hasMany(SiteFooter::class, 'parent');
    }

    public static function getArray($tab_id)
    {
        $tmp = array();

        foreach (SiteFooter::where('parent','=',$tab_id)->get() as $item)
        {
            $tmp[$item->meta_key] = $item->meta_value;
        }

        return $tmp;
    }

}
