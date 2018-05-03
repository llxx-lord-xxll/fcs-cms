<?php

namespace App\Admin\Databases\Website;

use Illuminate\Database\Eloquent\Model;

class SiteTemplateMetaValues extends Model
{
    protected $table = 'site_templates_meta_values';
    protected $fillable = ['meta_key','meta_value'];
    public $timestamps = false;


    public function templates_meta()
    {
        return $this->belongsTo(SiteTemplatesMeta::class, 'template_meta_id');
    }
}
