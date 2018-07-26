<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteGalleryAlbums extends Model
{
    protected $table = 'site_gallery_albums';
    protected $fillable = array('title','slug');
    protected $casts = ['albums'=>'array'];

    public $timestamps = false;

    public static function allNodes()
    {
        $ret = array();

        foreach (SiteGalleryAlbums::get() as $item)
        {
            $ret[$item->id] = $item->title;
        }

        return $ret;
    }


}
