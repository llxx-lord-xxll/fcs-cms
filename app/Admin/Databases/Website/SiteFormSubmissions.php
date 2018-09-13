<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteFormSubmissions extends Model
{
    protected $table = 'site_forms_submissioon';

    public function data()
    {
        return $this->hasMany(SiteFormData::class, 'submission_id');
    }
}
