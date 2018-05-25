<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteSettings;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
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

            $content->body($this->form()->edit(1)->setAction(route('settings.general.update',['general'=>'1'])));
        });
    }


    protected function form()
    {

        return Admin::form(SiteSettings::class, function (Form $form) {
            foreach (SiteSettings::get() as $item)
            {
                $form_elem = call_user_func_array(array($form, $item->type), array('input_'.$item->meta_key, $item->title));
                $form->ignore('input_'.$item->meta_key);


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

            $form->saving(function ($form)
            {

                foreach (SiteSettings::get() as $item)
                {
                    if (request('input_'.$item->meta_key) !=null)
                    {
                        $val =  request('input_'.$item->meta_key);
                        switch ($item->type)
                        {
                            case 'image':
                                if (request()->file('input_'.$item->meta_key) !=null)
                                {
                                    $f = request()->file('input_'.$item->meta_key);
                                    $fname = $f->getClientOriginalName(). uniqid(). "." .$f->guessClientExtension();
                                    $f->move(Storage::disk('site_upload')->path('images/'),$fname);
                                    $val = 'images/' . $fname;
                                }
                                break;
                            default:

                                break;

                        }

                        SiteSettings::find($item->id)->update(['meta_value'=>$val]);
                    }
                }
                return;
            });

            $form->saved(function($form)
            {
               return redirect()->route('settings.general.index');
            });

        });
    }
}
