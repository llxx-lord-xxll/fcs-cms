<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteFooter;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Tab;

class FooterController extends Controller
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

            $content->header('Edit Footer');
            $content->description('Modify the fields below');
            $tabs = new Tab();
            foreach (SiteFooter::getTabs() as $tab_id => $tab)
            {
                $tabs->add($tab,$this->form($tab_id));
            }

            $content->body($tabs);

        });
    }

    public function update($id)
    {

        dump(request()->all());
    }


    protected function form($tab_id)
    {
        $form = new \Encore\Admin\Widgets\Form(SiteFooter::getArray($tab_id));
        $form->action(route('footer.update',['footer'=>'1']));
        $form->method('post');
        $form->hidden('_token')->default(csrf_token());
        $form->hidden('_method')->default('PUT');


        foreach (SiteFooter::where('parent','=',$tab_id)->get() as $item)
        {
            $form_elem = null;

            switch ($item->meta_type)
            {
                case 'hasMany':
                    $form_elem =  call_user_func_array(array($form, $item->meta_type), array('listItems_'.$item->id, $item->title,function (Form\NestedForm $form)use($item)
                    {
                        $form->hidden('meta_val_'.$item->id)->value($item->id);
                        $form->text('__meta_key');
                    }));

                    break;
                case 'image':
                    $form_elem =  call_user_func_array(array($form, $item->meta_type), array($item->meta_key, $item->title));
                    $form_elem->help('To change the logo, please upload one');
                    break;
                default:
                    $form_elem =  call_user_func_array(array($form, $item->meta_type), array($item->meta_key, $item->title));
                    $form_elem->attribute(['value'=>$item->meta_value]);
                    break;
            }
        }

        return $form;



    }
}
