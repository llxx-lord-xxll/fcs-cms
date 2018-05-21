<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteGalleryAlbumMeta extends Model
{
    protected $table = 'site_gallery_meta';

    public $timestamps = false;
    public static function getAlbums($model)
    {
        $ret = array();
        if ($model != null)
        {
            $t = 0;
            foreach (SiteGalleryAlbumMeta::where('gallery_id','=',$model)->get() as $item)
            {
                $ret[$t] = $item->album_id;
                $t++;
            }
        }
        return $ret;
    }

    public static function getGalleries($model)
    {
        $ret = array();
        if ($model != null)
        {
            $t = 0;
            foreach (SiteGalleryAlbumMeta::where('album_id','=',$model)->get() as $item)
            {
                $ret[$t] = $item->gallery_id;
                $t++;
            }
        }
        return $ret;
    }
}
