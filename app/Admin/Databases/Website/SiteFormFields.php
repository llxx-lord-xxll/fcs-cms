<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteFormFields extends Model
{
    protected $table = 'site_form_fields';

    public function data()
    {
        return $this->hasMany(SiteFormData::class, 'field_id');
    }
}
