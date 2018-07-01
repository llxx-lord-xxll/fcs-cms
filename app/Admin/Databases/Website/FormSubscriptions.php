<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class FormSubscriptions extends Model
{
    protected $table = 'site_forms_subscription';
    protected $fillable = ['form_field_name','list_field_name','subscription_type'];
    public $timestamps = false;
    public function form()
    {
        return $this->belongsTo(SiteForms::class, 'form_id');
    }
}
