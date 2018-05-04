<?php

namespace App\Admin\Databases\Website;

use Encore\Admin\Form;
use Illuminate\Database\Eloquent\Model;

class SitePages extends Model
{
    protected $table = 'site_pages';
    protected $fillable = ['meta_type','template_id','meta_key','meta_value'];

    public function page_metas()
    {
        return $this->hasMany(SitePagesMeta::class, 'pages_id');
    }

    public static function formOptions(Form &$form,$id,$parent_id = 0)
    {
        if(SiteTemplatesMeta::where('templates_id','=',SitePages::find($id)->template_id)->where('parent_id','=',$parent_id)->first() == null)
        {
            if ($parent_id == 0)
            {
                return false;
            }

            Widgets::form_widget_display($form,SiteTemplatesMeta::find($parent_id)->widgets_id,SiteTemplatesMeta::find($parent_id)->id);
        }
        else
        {
            foreach (SiteTemplatesMeta::where('templates_id','=',SitePages::find($id)->template_id)->where('parent_id','=',$parent_id)->get() as $row)
            {
                $form->html('<h3/>'.$row->title.'</h3>');
                static::formOptions($form,$id,$row->id);
                $form->divider();
            }
        }
        return true;
    }
}
