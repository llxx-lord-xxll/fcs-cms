<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteForms extends Model
{
    protected $table = 'site_forms';

    public $timestamps = false;

    public function entries()
    {
        return $this->hasMany(FormEntries::class, 'form_id');
    }
    public function subscriptions()
    {
        return $this->hasMany(FormSubscriptions::class, 'form_id')->where('subscription_type','=','subscription');
    }
    public function newslettersub()
    {
        return $this->hasMany(FormSubscriptions::class, 'form_id')->where('subscription_type','=','newsletter');
    }


   public static function list_fields($table_name)
   {
       $fields = DB::getSchemaBuilder()->getColumnListing($table_name);
       $retfields = array();
       foreach ($fields as $field)
       {
           $retfields[$field] = $field;
       }
       return $retfields;
   }
    public static function allNodes()
    {
        $ret = array();

        foreach (SiteForms::get() as $item)
        {
            $ret[$item->id] = $item->title;
        }

        return $ret;
    }
}
