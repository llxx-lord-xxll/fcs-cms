<?php

namespace App\Admin\Databases\Website;

use Carbon\Carbon;
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
        $options = SiteTemplatesMeta::where('templates_id', "=",$template_id)->orderByRaw("`order` = '0', `order` ASC")->get()->toArray();
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

    public static function clone_item($item_id,$parent=null)
    {

       $target =  DB::table('site_templates_meta')->find($item_id);
       $meta = new SiteTemplatesMeta();
       $meta->templates_id = $target->templates_id;
       if ($parent !=null)
       {
           $meta->parent_id = $parent;
       }
       else
       {
           $meta->parent_id = $target->parent_id;
       }
        $meta->order = $target->order;
        $meta->widgets_id = $target->widgets_id;
        $meta->title = $target->title;
        $meta->created_at = Carbon::now()->toDateTimeString();
        $meta->updated_at = Carbon::now()->toDateTimeString();
        $meta->save();

        foreach (SiteTemplateMetaValues::where('template_meta_id','=',$target->id)->get() as $item)
        {
            $meta_value = new SiteTemplateMetaValues();
            $meta_value->template_meta_id = $meta->id;
            $meta_value->meta_key = $item->meta_key;
            $meta_value->meta_value = $item->meta_value;
            $meta_value->save();
        }

        foreach (SiteTemplatesMeta::where('parent_id','=',$target->id)->get() as $item)
        {
            self::clone_item($item->id,$meta->id);
        }
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
