<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases;

use App\Wid;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class WidgetController extends Controller
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

            $content->header('Widgets');
            $content->description('Manage your website widgets');
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

            $content->header('Edit Widget');
            $content->description('Edit your widget');

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

            $content->header('Create widgets');
            $content->description('Create your widgets');

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
        return Admin::grid(Databases\Website\Widgets::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column("title");
            $grid->column("slug");

        });

    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {

        return Admin::form(Databases\Website\Widgets::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('title');
            $form->text('slug')->placeholder("No space,only alphanum chars")->rules("required|regex:/^[A-Za-z_][A-Za-z\d_]*$/");
            $form->hasMany("widget_entries",function (Form\NestedForm $form)
            {
                $form->text("title","Caption");
                //var_dump(Databases\Website\Widgets::selectOptions());
                //var_dump(Databases\Website\WidgetEntries::selectOptions());
                $form->select("field_type","Type")->options(Databases\Website\Widgets::selectOptions());
            });
        });
    }
}
