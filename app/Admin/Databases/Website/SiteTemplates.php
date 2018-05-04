<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class SiteTemplates extends Model
{

    protected $fillable = ['title', 'layout_id', 'slug', 'author', 'updated_at'];
    protected $table = 'site_templates';

    public static function selectOptions()
    {
        $tmp = array();
        foreach ( DB::table('site_templates')->select(['id','title'])->get() as $row)
        {
            $tmp[$row->id] = $row->title;
        }

        return $tmp;
    }
}
