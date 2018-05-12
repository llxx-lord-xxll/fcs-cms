<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Layouts extends Model
{

    protected $table = 'layouts';
    protected $fillable = ['title', 'slug'];

    public static function selectOptions()
    {
        $tmp = array();
        foreach ( DB::table('layouts')->select(['id','title'])->get() as $row)
        {
            $tmp[$row->id] = $row->title;
        }
        return $tmp;
    }
}
