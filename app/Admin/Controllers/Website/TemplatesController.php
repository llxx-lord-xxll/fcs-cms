<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\Layouts;
use App\Admin\Databases\Website\SiteTemplates;

use App\User;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Auth;

class TemplatesController extends Controller
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

            $content->header('Templates');
            $content->description('Manage your templates');

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

            $content->header('Edit Templates');
            $content->description('Edit your templates');

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

            $content->header('Create Template');
            $content->description('Create the templates by just filling these data');

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
        return Admin::grid(SiteTemplates::class, function (Grid $grid) {


            $grid->column('title','Title');
            $grid->column('layout_id','Layout')->display(function ($layout_id)
            {
               return "<a href='layouts/$layout_id/edit'>".Layouts::find($layout_id)->title."</a>" ;
            });
            $grid->column('slug');
            $grid->column('author')->display(function ($author)
            {
                return Administrator::find($author)->name;
            });
            $grid->updated_at()->display(function ($updated_At){
                return Carbon::parse($updated_At)->format("d M, Y");
            });

            $grid->actions(function (Grid\Displayers\Actions $actions)
            {
                $actions->prepend('<a href="templates/'.$actions->getKey().'/meta" title="Edit Widgets"> <span class="fa fa-gears" ></span> </a>');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteTemplates::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('title');
            $form->text('slug');
            $form->select('layout_id','Layout')->options(Layouts::selectOptions())->default(Layouts::first()->id);
            $form->hidden('author');
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saving(function (Form $form) {
                $form->user_id = Admin::user()->getAuthIdentifier();
            });

        });
    }
}
