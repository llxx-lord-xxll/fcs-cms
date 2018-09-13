<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteFormData extends Model
{
    protected $table = 'site_forms_data';

    public function submission()
    {
        return $this->belongsTo(SiteFormSubmissions::class, 'submission_id');
    }
    public function field()
    {
        return $this->belongsTo(SiteFormFields::class, 'field_id');
    }

}
