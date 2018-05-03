<?php

namespace App\Admin\Databases\Website;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteTemplatesMeta extends Model
{
    use AdminBuilder,ModelTree {
        ModelTree::boot as treeBoot;
    }

    protected $table = 'site_templates_meta';

    public static function selectedOptions2($template_id)
    {
        $options = SiteTemplatesMeta::where('templates_id', "=",$template_id)->orderby('order')->get()->toArray();
        if (!empty($options))
            $options = (new static())->buildSelectOptions($options);

        return collect($options)->prepend('Root',0)->all();
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('order');
        $this->setTitleColumn('title');
    }



    public function templates_meta_values()
    {
        return $this->hasMany(SiteTemplateMetaValues::class, 'template_meta_id');
    }

    protected static function boot()
    {
        static::treeBoot();

    }
}
