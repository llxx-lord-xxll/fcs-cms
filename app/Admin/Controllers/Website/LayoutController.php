<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\Layouts;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;


class LayoutController extends Controller
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

            $content->header('Layouts');
            $content->description('Manage your website layouts');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Edit layout');
            $content->description('Edit your layout');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('Create Layout');
            $content->description('Create a new website layout');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Layouts::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('title')->sortable();
            $grid->column('slug')->sortable();
            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */

    protected function form()
    {
        return Admin::form(Layouts::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('title');
            $form->text('slug');
            $form->ignore('content');


            $form->aceditor('content')->default(function ($form) {
                //$layout = Layouts::find($id);
                if(Storage::disk("site_layout")->exists($form->model()->slug.".blade.php"))
                {
                    return Storage::disk("site_layout")->get($form->model()->slug.".blade.php");
                }
                else

                $error = new MessageBag([
                    'title'   => 'Layout Not Found Exception',
                    'message' => 'Layout file not found in the layout folder in the website, check your slug'
                ]);

                return back()->with(compact('error'));

                //return $tuple->id;
            });

            $form->saved(function (Form $form) {
                if(Storage::disk("site_layout")->exists($form->model()->slug.".blade.php"))
                {
                   return Storage::disk("site_layout")->put($form->model()->slug.".blade.php",request('content'));
                }
            });


        });
    }
}
