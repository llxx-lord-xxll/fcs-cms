<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteSettings;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class GeneralSettingsController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('General Settings');
            $content->description('Website Settings ');

            $content->body(new Box('General Settings',$this->form()));
        });
    }

    public function update($id)
    {

        foreach (SiteSettings::get() as $item)
        {
            if (request($item->meta_key) !=null)
            {
                $val =  request($item->meta_key);
                SiteSettings::find($item->id)->update(['meta_value'=>$val]);
            }
        }
        return redirect(route('settings.general.index'));
    }

    protected function form()
    {
        $form = new \Encore\Admin\Widgets\Form(SiteSettings::getArray());
        $form->action(route('settings.general.update',['general'=>'1']));
        $form->method('post');
        $form->hidden('_token')->default(csrf_token());
        $form->hidden('_method')->default('PUT');
        foreach (SiteSettings::get() as $item)
        {
            $form_elem = call_user_func_array(array($form, $item->type), array($item->meta_key, $item->title));

            switch ($item->type)
            {
                case 'image':
                    $form_elem->help('To change the logo, please upload one');
                    break;
                default:
                    $form_elem->attribute(['value'=>$item->meta_value]);
                    break;
            }
        }



        return $form;
    }
}
