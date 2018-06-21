<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class FormEntries extends Model
{
    protected $table = 'site_form_entries';
    protected $fillable = ['field_name', 'field_title', 'field_rules','field_instructions','field_type','field_ivals','field_placeholder'];
    public function form()
    {
        return $this->belongsTo(SiteForms::class, 'form_id');
    }

}
