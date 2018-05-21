<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteGalleryPhotos extends Model
{
    protected $table = 'site_gallery_photos';


    protected $casts = ['albums'=> 'array'];

}
