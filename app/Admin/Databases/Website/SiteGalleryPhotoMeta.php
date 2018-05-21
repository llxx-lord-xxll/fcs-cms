<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteGalleryPhotoMeta extends Model
{
    protected $table = 'site_gallery_photos_meta';

    protected $primaryKey = 'photo_id,album_id';

    public $timestamps = false;

    public static function getAlbums($model)
    {
        $ret = array();
        if ($model != null)
        {
            $t = 0;
            foreach (SiteGalleryPhotoMeta::where('photo_id','=',$model)->get() as $item)
            {
                $ret[$t] = $item->album_id;
                $t++;
            }
        }
        return $ret;
    }

    public static function getPhotos($model)
    {
        $ret = array();
        if ($model != null)
        {
            $t = 0;
            foreach (SiteGalleryPhotoMeta::where('album_id','=',$model)->get() as $item)
            {
                $ret[$t] = $item->photo_id;
                $t++;
            }
        }
        return $ret;
    }
}
