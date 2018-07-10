<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteFooter;

use App\Admin\Databases\Website\SiteMenus;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Tab;
use GuzzleHttp\Psr7\Request;

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
            $box = new Box(' ');
            $box->content($this->form());
            $content->row($box);
        });
    }

    public function update(\Illuminate\Http\Request $request)
    {
        $request = array_except($request->all(),['_token','_method']);
        foreach ($request as $key => $val)
        {
           $footer =  SiteFooter::firstOrNew(array('meta_key'=>$key));
           $footer->meta_value = $val;
           $footer->save();
        }

        return redirect(route('footer.index'));
    }


    protected function form()
    {
            $form =  new \Encore\Admin\Widgets\Form(SiteFooter::getArray());
            $form->method('post');
            $form->hidden('_token')->default(csrf_token());
            $form->hidden('_method')->default('PUT');
            $form->action(route('footer.update',['footer'=>1]));

            $form->kcimage('logo','Left Logo');
            $form->divide();

            $form->select('sel_menu1','Menu Slot 1')->options(SiteMenus::allMenus());
            $form->select('sel_menu2','Menu Slot 2')->options(SiteMenus::allMenus());
            $form->select('sel_menu3','Menu Slot 3')->options(SiteMenus::allMenus());
            $form->select('sel_menu4','Menu Slot 4')->options(SiteMenus::allMenus());

            $form->divide();

            $form->select('sel_menu5','Social Icon Menu')->options(SiteMenus::allMenus());
            $form->divide();

            $form->text('copyright','Copyright Text');
            $form->select('sel_menu6','Subfooter Menu')->options(SiteMenus::allMenus());

      return $form;
    }
}
